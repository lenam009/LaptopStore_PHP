<?php
spl_autoload_register(function ($class) {
    require "../class/{$class}.php";
});
include'../init.php';

//TAO BIEN ĐỂ TRUYỀN VAO ERROR
$result = "";
$errorImage = "";


//LẤY DANH SÁCH LOẠI SẢN PHẨM
$typeProduct = TypeProduct::getAllProduct($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //TẠO MỘT SẢN PHẨM VÀ CẬP NHẬT DỮ LIỆU MÀ NGƯỜI DÙNG NHẬP CHO SẢN PHẨM ĐÓ
    $newProduct = new Product();
    //LẤY SẢN PHẨM ĐANG UPDATE BẰNG IDPRODUCT
    //CẬP NHẬT LẠI ẢNH CHO NEWPRODUCT
    $newProduct->imageProduct = $_POST['imageProduct'];
    //CẬP NHẬT DỮ LIỆU MÀ NGƯỜI DÙNG NHẬP
    $newProduct->idProduct = $_POST['idProduct'];
    $newProduct->nameProduct = $_POST['nameProduct'];
    $newProduct->contentProduct = $_POST['contentProduct'];
    $newProduct->priceProduct = $_POST['priceProduct'];
    $newProduct->screen = $_POST['screen'];
    $newProduct->CPU = $_POST['CPU'];
    $newProduct->RAM = $_POST['RAM'];
    $newProduct->hardDrive = $_POST['hardDrive'];
    //CHECK ID TYPE CHO TRƯỜNG HỢP NULL
    $_POST['idTypeProduct'] = $_POST['idTypeProduct'] == "null" ? null : $_POST['idTypeProduct'];
    $newProduct->idTypeProduct = $_POST['idTypeProduct'] ?? null;

    //XỬ LÝ ẢNH THEO YÊU CẦU NGƯỜI DÙNG
    if (isset($_POST['yes'])) {
        if (isset($newProduct->imageProduct)) {
            unlink('../images/' . $newProduct->imageProduct);
            $newProduct->imageProduct = null;
        }
    } else {
        if (isset($_FILES['file']) && $_FILES['file']['error'] != UPLOAD_ERR_NO_FILE) {
            try {
                switch ($_FILES['file']['error']) {
                    case UPLOAD_ERR_OK:
                        break;
                    default:
                        throw new Exception("An error occured");
                }

                //CHECK SIZE FILE
                if ($_FILES['file']['size'] > 1000000) {
                    throw new Exception("File too large");
                }

                //CHECK ĐÚNG ĐỊNH DANG ĐUÔI FILE
                $mime_types = ['image/png', 'image/jpeg', 'image/gif'];
                $file_info = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($file_info, $_FILES['file']['tmp_name']);
                if (!in_array($mime_type, $mime_types)) {
                    throw new Exception("Mime type invalid");
                }

                //TRƯỜNG HỢP PRODUCT ĐÃ CÓ ẢNH
                if (isset($newProduct->imageProduct) && !empty($newProduct->imageProduct)) {
                    $fname = $newProduct->imageProduct;
                    unlink('../images/' . $fname);
                    $dest = '../images/' . $fname;
                }
                //TRƯỜNG HOP PRODUCT CHƯA CÓ ẢNH
                else {
                    //ĐỔI TÊN FILE
                    $pathinfo = pathinfo($_FILES['file']['name']);
                    $extension = $pathinfo['extension'];
                    $fname = 'image.' . $extension;

                    //TẠO ĐƯỜNG DẪN
                    $dest = '../images/' . $fname;
                    $i = 1;
                    while (file_exists($dest)) {
                        $fname = 'image' . "__$i." . $extension;
                        $dest = '../images/' . $fname;
                        $i++;
                    }
                }
                //UPLOAD FILE
                if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
                    $newProduct->imageProduct = $fname;
                } else {
                    throw new  Exception("Unable move file");
                }
            } catch (Exception $e) {
                $errorImage = $e->getMessage();
            }
        }
    }
    //UPDATE SẢN PHẨM
    if (empty($errorImage) && $newProduct->updateProduct($pdo)) {
        $result = "Update successful";
    } else {
        $result = "Update failed";
    }
} //CẬP NHẬT DỮ LIỆU VÀO INPUT(MỚI VÀO PAGE UPDATE) 
else {
    if (isset($_GET['id'])) {
        $newProduct = Product::getOneProductById($_GET['id'], $pdo);
        if (empty($newProduct))
            die('Product not exists');
    } else {
        die("Product not exists");
    }
}

?>

<?php include '../admin/header.php' ?>

<div class="container mt-3 mb-3">
    <div class="w-75 m-auto ">
        <h3 class="font-weight-bold text-center">Update product</h3>
        <form method="post" enctype="multipart/form-data">
            <table class="table table-borderless table-info">
                <tr class="">
                    <td class="w-25"><label for="name">Name</label></td>
                    <td><input name="nameProduct" id="name" value="<?= $newProduct->nameProduct ?>" type="text" required class="form-control" /></td>
                </tr>

                <tr>
                    <td><label for="image">Image</label></td>
                    <td>
                        <div class="row">
                            <div class="col-3">
                                <img src="../images/<?= $newProduct->imageProduct ?>" width="150" height="120" />
                                <input name='imageProduct' value="<?= $newProduct->imageProduct ?>" hidden />
                            </div>
                            <div class="col-9">
                                <input id="image" type="file" name="file" class="form-control" />
                                <div class="row mt-2">

                                    <?php if (isset($_GET['action']) && $_GET['action'] == 'delete' && empty($result)) : ?>
                                        <div class="row m-0 mt-2">
                                            <h5 class="text-danger p-0 ml-3">Are you sure delete this image?</h5>
                                            <button class="btn btn-danger ml-2" style="margin-top: -1%;" type="submit" name="yes" value="yes">Yes</button>
                                        </div>
                                    <?php else : ?>
                                        <div class="row m-0">
                                            <a class="ml-3 btn btn-danger font-weight-bold" href="../admin/updateProduct.php?action=delete&id=<?= $newProduct->idProduct ?>">Delete image</a><br />
                                        </div>
                                    <?php endif; ?>

                                    <h5 class="text-danger ml-2"><?= $errorImage ?></h5>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><label for="content">Content</label></td>
                    <td> <textarea id="content" name="contentProduct" class="form-control" maxlength="250"><?= $newProduct->contentProduct ?></textarea></td>
                </tr>
                <tr>
                    <td><label for="price">Price</label></td>
                    <td><input id="price" name="priceProduct" value="<?= $newProduct->priceProduct ?>" type="number" required class="form-control" /></td>
                </tr>
                <tr>
                    <td>Type Product</td>
                    <td>
                        <div class="row">
                            <div class="ml-3 mr-2">
                                <input checked type="radio" class="" name="idTypeProduct" value="null" id="null">
                                <label class="" for="null">Other</label>
                            </div>
                            <?php foreach ($typeProduct as $product) : ?>
                                <div class="ml-3 mr-2">
                                    <?php if ($newProduct->idTypeProduct == $product->idTypeProduct) : ?>
                                        <input type="radio" checked class="" name="idTypeProduct" value="<?= $product->idTypeProduct ?>" id=<?= $product->idTypeProduct ?> />
                                    <?php else : ?>
                                        <input type="radio" class="" name="idTypeProduct" value="<?= $product->idTypeProduct ?>" id=<?= $product->idTypeProduct ?> />
                                    <?php endif; ?>
                                    <label class="" for="<?= $product->idTypeProduct ?>"><?= $product->nameTypeProduct ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><label for="screen">Screen</label></td>
                    <td><input id="screen" name="screen" type="text" value="<?= $newProduct->screen ?>" class="form-control" /></td>
                </tr>
                <tr>
                    <td><label for="cpu">CPU</label></td>
                    <td><input id="cpu" name="CPU" value="<?= $newProduct->CPU ?>" type="text" class="form-control" /></td>
                </tr>
                <tr>
                    <td><label for="ram">Ram</label></td>
                    <td><input id="ram" name="RAM" value="<?= $newProduct->RAM ?>" type="text" class="form-control" /></td>
                </tr>
                <tr>
                    <td><label for="hardDrive">Hard Drive</label></td>
                    <td><input name="hardDrive" id="hardDrive" value="<?= $newProduct->hardDrive ?>" type="text" class="form-control" /></td>
                </tr>
                <tr>
                    <td class="">
                        <h5 class="text-danger"><?= $result ?></h5>
                    </td>
                    <td class="text-right">
                        <button class="btn btn-primary" name="submit" value="submit">Submit</button>
                        <input type="hidden" name="idProduct" value="<?= $newProduct->idProduct ?>" />
                    </td>
                </tr>
            </table>
        </form>

    </div>

</div>



<?php include '../footer.php' ?>