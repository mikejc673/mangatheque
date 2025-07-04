<?php
$title = "Update User";
?>
<h1>Update User</h1>
<div class="user">
    <form action="/mangatheque/user/update/<?= $user->id->getPseudo(); ?>" method="post">
    <h2><?= $user->id->getPseudo(); ?></h2>
    <label for="pseudo">Pseudo:</label>
    <input type="text" id="pseudo" name="pseudo" value="<?= $user->pseudo;  ?>">
    <p>Email: <?= $user->email; ?></p>
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" value="<?= $user->email; ?>">
    <p>Password: <?= $user->password; ?></p>
    <label for="password">Password:</label>
    <input type="text" id="password" name="password" value="<?= $user->password; 
    $user->getPassword();?>">

</div>

   
    <button type="submit">Update</button>
</form>
<?php
$content = ob_get_contents();
ob_end_clean();
require_once './view/base-html.php';

?>