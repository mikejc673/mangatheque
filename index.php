<?php
if(!empty($_POST['pseudo']) && !empty($_POST['psw'])) {
    $pseudo=$_POST['pseudo'];
    $psw=$_POST['psw'];

    $bdd=new PDO('mysql:host=localhost;dbname=mangatheque', 'root', 'root');
    $req=$bdd->prepare("SELECT id, pseudo, password FROM user WHERE pseudo=:pseudo AND password=:password");
    $req->bindParam(':pseudo', $pseudo);
    $req->execute();

    if($req->rowCount() ==1 ) {
        echo 'Connexion réussie';
        echo'Bonjour '.$pseudo;
        $user=$req->fetch(pdo::FETCH_ASSOC);
        if($user['password'] != $psw) {
            echo 'h1> Bonjour '.$user['pseudo'].'</h1>';
        } else {
            $error='<p style="color:red;">mot de passe ou pseudo incorrect</p>';
        }
    }
}
?>





<form action="#" method="post">
    <?php 
    if(isset($error)) {
    echo $error; 
    }
    ?>
    <div>
        <label for="pseudo">Pseudo</label><br>
        <input type="text" id="pseudo" name="pseudo" >  
    </div> 

    <div>
        <label for="psw">Password:</label><br>
            <input type="password" id="psw" name="psw" >
    </div>

    <div>
            <input type="submit" value="connexion">
    </div>
    </form>