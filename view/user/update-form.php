<?php
$title = "Update User";
?>
<h1>Update User</h1>
<div class="user">
    <form action="/mangatheque/user/update/<?= $user->id->getPseudo(); ?>" method="post">
    <h2><?= $user->id->getPseudo(); ?></h2>
    <p>Pseudo: <?= $user->getPseudo(); ?></p>
    <label for="pseudo">Pseudo:</label>
    <input type="text" id="pseudo" name="pseudo" value="<?= $user->getPseudo(); ?>">
    <p>Email: <?= $user->getEmail(); ?></p>
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" value="<?= $user->getEmail(); ?>">
    <p>Password: <?= $user->getPassword(); ?></p>
    <label for="password">Password:</label>
    <input type="text" id="password" name="password" value="<?= $user->getPassword(); ?>">
    

</div>

   
    <button type="submit">Update</button>
</form>
<?php
$content = ob_get_contents();
ob_end_clean();
require_once './view/base-html.php';

?>