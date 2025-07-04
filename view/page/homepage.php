<?php
$title = "Page d'accueil !";
ob_start();
foreach($users as $user) :
?>
<div class="user">
    <h2><?= $user->getPseudo() ?></h2>
    <p>Email : <?= $user->getEmail() ?></p>
    <p><a href="user/<?= $user->getId() ?> ">Voir le user</a></p>
    <p><a href="user/update/<?= $user->getId() ?>">Modifier le user</a></p>
    <p><a href="user/delete/<?= $user->getId() ?>">Supprimer le user</a></p>
</div>
<?php
endforeach;
$content = ob_get_contents();
ob_end_clean();
require_once './view/base-html.php';