<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Connexion à la base de données
        $pdo = new PDO('mysql:host=localhost;dbname=db', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $email = $_POST['email'];
        $pass = $_POST['pass'];

        // Sélectionner l'utilisateur avec l'email
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        
        // Vérifier si l'utilisateur existe et que le mot de passe est correct
        if ($user) {
            // Comparer les mots de passe en clair
            if ($pass === $user['password']) {
                // L'utilisateur existe et le mot de passe est correct
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['user']['isLoggedIn'] = true;

                // Redirection selon le rôle de l'utilisateur
                if ($user['role'] == 'admin') {
                    header("Location: /Projet/index.php"); // Page pour l'admin
                } elseif ($user['role'] == 'guest') {
                    header("Location: /Projet/com.php"); // Page pour le guest
                } else {
                    header("Location: /Projet/index.php"); // Redirection par défaut
                }
                exit;
            } else {
                // Le mot de passe ne correspond pas
                $error = "Email ou mot de passe invalide (mot de passe incorrect).";
            }
        } else {
            // Si l'email n'existe pas
            $error = "Email ou mot de passe invalide (email incorrect).";
        }
    } catch (PDOException $e) {
        // En cas d'erreur de connexion à la base de données
        $error = "Erreur de connexion à la base de données.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/darkly/bootstrap.min.css">
</head>
<body>
    <div class="container pt-5">
        <h1>Login</h1>
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="email" name="email" class="form-control my-2" placeholder="Email" required>
            <input type="password" name="pass" class="form-control my-2" placeholder="Password" required>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <div class="text-center mt-3">
            <a href="http://localhost/Projet/register.php">Créer un compte</a>
        </div>
    </div>
</body>
</html>
