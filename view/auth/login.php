<?php
$title="login";
ob_start();
?>


<form action="/mangatheque/login" method="POST">
   <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Enter your email" required>
    </div>
    <br>
     <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>
    </div>
    <br>
     <div>
        <input type="submit" value="Register">
    </div>
    <br>
</form>
<?php
$content=ob_get_contents();
ob_end_clean();
?>