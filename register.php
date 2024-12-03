<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Connexion à la base de données
        $pdo = new PDO('mysql:host=localhost;dbname=db', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer les informations du formulaire
        $email = $_POST['email'];
        $pass = $_POST['pass'];

        // Vérifier si l'email existe déjà
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si l'email existe déjà
        if ($existingUser) {
            $error = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
        } else {
            // Si l'email n'existe pas, insérer l'utilisateur avec le rôle "guest"
            $sql = "INSERT INTO users (email, password, role) VALUES (?, ?, 'guest')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email, $pass]);

            // Rediriger vers la page de connexion après l'ajout de l'utilisateur
            header("Location: /Projet/login.php");
            exit;
        }
    } catch (PDOException $e) {
        // En cas d'erreur
        $error = "Erreur lors de la création du compte.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/darkly/bootstrap.min.css">
</head>
<body>
    <div class="container pt-5">
        <h1>Register</h1>
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="email" name="email" class="form-control my-2" placeholder="Email" required>
            <input type="password" name="pass" class="form-control my-2" placeholder="Password" required>
            <button type="submit" class="btn btn-success w-100">Create Account</button>
        </form>
        <div class="text-center mt-3">
            <a href="/login.php">Already have an account? Login</a>
        </div>
    </div>
</body>
</html>
