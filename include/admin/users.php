<?php
spl_autoload_register(function ($class) {
    require "../class/{$class}.php";
});
include'../init.php';

$users = User::getAllUsers($pdo);
?>

<?php include '../admin/header.php' ?>

<div class="w-75 m-auto">
    <a class="btn btn-primary mt-2 mb-2" href="../admin/addUser.php">Add user</a>
    <table class="table">
        <thead class="thead-dark">
            <tr class="text-center">
                <th class="text-left">No.</th>
                <th class="text-left">Email</th>
                <th>Password</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            foreach ($users as $user) : ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $user->email ?></td>
                    <td class=""><input disabled type="password" class="form-control border-0 bg-white" value="<?= $user->password ?> ?>" /></td>
                    <td>
                        <a class="btn btn-primary" href="../admin/user.php?email=<?= $user->email ?>">Edit</a>
                    </td>
                </tr>
            <?php $i++;
            endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../footer.php' ?>