<?php
$title="register";
ob_start();
?>
<?php
$content=ob_get_contents();
ob_end_clean();
?>
<form action="/mangatheque/register" method="post">
    <div>
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo" placeholder="Enter your pseudo" required>
    </div>
    <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Enter your email" required>
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required><br>
   


    </div>
    <br>
    <div>
        <input type="submit" value="Register">
    </div>

</form>

<?php
require __DIR__.'/../base-html.php';