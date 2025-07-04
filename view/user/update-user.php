<?php
$title = "Update User";
?>
<h1>Update User</h1>
<div class="user">
    <h2><?php echo "User ID: " . $user->id; ?></h2>
    <p>Email: <?php echo $user->email; ?></p>
    <p>Created at: <?php echo $user->created_at; ?></p>
</div>
<form action="/mangatheque/user/update/<?php echo $user->id; ?>" method="post">
    <input type="text" name="name" value="<?php echo $user->name; ?>">
    <input type="email" name="email" value="<?php echo $user->email; ?>">
    <button type="submit">Update</button>
</form>
<?php
$content = ob_get_contents();
ob_end_clean();
require_once './view/base-html.php';

?>