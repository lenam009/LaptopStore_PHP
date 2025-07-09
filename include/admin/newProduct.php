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
$newProduct = new Product();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //TẠO MỘT SẢN PHẨM VÀ CẬP NHẬT DỮ LIỆU CHO SẢN PHẨM ĐÓ
    $newProduct->nameProduct = $_POST['nameProduct'];
    $newProduct->contentProduct = $_POST['contentProduct'];
    $newProduct->priceProduct = $_POST['priceProduct'];
    $newProduct->idTypeProduct = $_POST['idTypeProduct'] ?? null;
    $newProduct->screen = $_POST['screen'];
    $newProduct->CPU = $_POST['CPU'];
    $newProduct->RAM = $_POST['RAM'];
    $newProduct->hardDrive = $_POST['hardDrive'];

    //THÊM SẢN PHẨM
    if (isset($_FILES['file'])) {
        try {
            switch ($_FILES['file']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                    //CHECK FILE ĐÃ UPLOAD CHUA
                case UPLOAD_ERR_NO_FILE:
                    throw new Exception("No file upload");
                default:
                    throw new Exception("An error occured");
            }

            if ($_FILES['file']['size'] > 1000000) {
                throw new Exception("File too large");
            }

            //CHECK ĐÚNG ĐỊNH DANG ĐUÔI FILE
            $mime_types = ['image/png', 'image/jpeg', 'image/gif'];
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($file_info, $_FILES['file']['tmp_name']);
            if (!in_array($mime_type, $mime_types)) {
                throw new Exception("Invalid mime type");
            }

            //ĐỔI TÊN FILE
            $pathinfo = pathinfo($_FILES['file']['name']);
            $extension = $pathinfo['extension'];
            $fname = 'image.' . $extension;

            //TAO ĐƯỜNG DẪN
            $dest = '../images/' . $fname;
            $i = 1;
            while (file_exists($dest)) {
                $fname = 'image' . '__' . $i . '.' . $extension;
                $dest = '../images/' . $fname;
                $i++;
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
    } else {
        $newProduct->imageProduct = null;
    }
    //THỰC HIỆN THÊM SẢN PHẨM
    if (empty($errorImage)) {
        $newProduct->addProduct($pdo);
        header('location: index.php');
    } else {
        $result = "Add failed";
    }
}

?>

<?php include '../admin/header.php' ?>

<div class="container mt-3 mb-3">
    <div class="w-75 m-auto ">
        <h3 class="font-weight-bold text-center">New product</h3>
        <form method="post" enctype="multipart/form-data">
            <table class="table table-borderless table-info">
                <tr class="">
                    <td class="w-25"><label for="name">Name</label></td>
                    <td><input name="nameProduct" id="name" type="text" value="<?= $newProduct->nameProduct ?>" required class="form-control" /></td>
                </tr>
                <tr>
                    <td><label for="file">Image</label></td>
                    <td>
                        <input type="file" name="file" class="form-control" id="file" />
                        <h4 class="text-danger"><?= $errorImage ?></h4><br />
                    </td>
                </tr>
                <tr>
                    <td><label for="content">Content</label></td>
                    <td>
                        <textarea id="content" name="contentProduct" class="form-control" maxlength="250"><?= $newProduct->contentProduct ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td><label for="price">Price</label></td>
                    <td><input name="priceProduct" value="<?= $newProduct->priceProduct ?>" id="price" type="number" required class="form-control" /></td>
                </tr>
                <tr>
                    <td><label>Type Product</label></td>
                    <td>
                        <div class="row">
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
                    <td><input name="screen" value="<?= $newProduct->screen ?>" id="screen" type="text" required class="form-control" /></td>
                </tr>
                <tr>
                    <td><label for="cpu">CPU</label></td>
                    <td><input name="CPU" id="cpu" value="<?= $newProduct->CPU ?>" type="text" required class="form-control" /></td>
                </tr>
                <tr>
                    <td><label for="ram">Ram</label></td>
                    <td><input name="RAM" id="ram" type="text" value="<?= $newProduct->RAM ?>" required class="form-control" /></td>
                </tr>
                <tr>
                    <td><label for="hardDrive">Hard drive</label></td>
                    <td><input name="hardDrive" id="hardDrive" value="<?= $newProduct->hardDrive ?>" type="text" required class="form-control" /></td>
                </tr>
                <tr>
                    <input type="hidden" name="null" value="<?= null ?>" />
                </tr>
                <tr>
                    <td>
                        <h5 class="text-danger"><?= $result ?></h5>
                    </td>
                    <td colspan="3" class="text-right"><button class="btn btn-primary" name="submit" value="submit">Submit</button></td>
                </tr>
            </table>
        </form>

    </div>

</div>



<?php include '../footer.php' ?>