<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est déjà connecté, si oui, le rediriger vers la page d'accueil
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("Location: " . BASE_URL . "mangas");
    exit;
}


$username_email = $password = "";
$username_email_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Vérifier si le nom d'utilisateur/email est vide
    if (empty(trim($_POST["username_email"]))) {
        $username_email_err = "Veuillez entrer votre nom d'utilisateur ou votre email.";
    } else {
        $username_email = trim($_POST["username_email"]);
    }

    // Vérifier si le mot de passe est vide
    if (empty(trim($_POST["password"]))) {
        $password_err = "Veuillez entrer votre mot de passe.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Valider les identifiants
    if (empty($username_email_err) && empty($password_err)) {
        $userModel = new User();
        $user = $userModel->findByUsernameOrEmail($username_email);
        if ($user) {
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $user['id'];
                $_SESSION["username"] = $user['username'];
                $_SESSION["email"] = $user['email'];
                $_SESSION['message'] = "Bienvenue, {$user['username']} ! Vous êtes connecté.";
                $_SESSION['message_type'] = 'success';
                $_SESSION['redirect'] = BASE_URL . 'mangas';
                $_SESSION['redirect_label'] = 'Voir la liste des mangas';
                header("Location: " . BASE_URL . "message");
                exit;
            } else {
                $login_err = "Nom d'utilisateur/email ou mot de passe invalide.";
            }
        } else {
            $login_err = "Nom d'utilisateur/email ou mot de passe invalide.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Mangathèque</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .wrapper { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 350px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; }
        input[type="text"], input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 5px;
        }
        .help-block { color: red; font-size: 0.9em; margin-top: 5px; display: block; }
        .login-error { color: red; text-align: center; margin-bottom: 15px; }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 1em;
            margin-top: 10px;
        }
        .btn:hover { background-color: #0056b3; }
        p { text-align: center; margin-top: 20px; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Connexion</h2>
        <?php if (!empty($login_err)): ?>
            <div class="login-error"><?php echo $login_err; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nom d'utilisateur ou Email</label>
                <input type="text" name="username_email" value="<?php echo htmlspecialchars($username_email); ?>">
                <span class="help-block"><?php echo $username_email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn" value="Se connecter">
            </div>
            <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a>.</p>
        </form>
    </div>
</body>
</html>