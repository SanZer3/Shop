<?php
session_start();

if (!isset($_SESSION['user']['isLoggedIn']) || $_SESSION['user']['role'] !== 'guest') {
    // Si l'utilisateur n'est pas connecté ou n'est pas un guest, redirection vers la page de login
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';
$showAccountInfo = false; // Variable pour afficher les informations du compte

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=db', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$userId = $_SESSION['user_id'];

// Affichage des informations du compte
if (isset($_POST['showAccountInfo'])) {
    try {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $showAccountInfo = true; // Afficher les informations du compte
        } else {
            $error = 'Impossible de récupérer les informations du compte.';
        }
    } catch (PDOException $e) {
        $error = 'Erreur lors de la récupération des informations.';
    }
}

// Traitement du formulaire de modification des informations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['showAccountInfo'])) {
    try {
        $newEmail = $_POST['email'];
        $newPassword = $_POST['password'];

        // Vérification de l'email et mot de passe non vides
        if (empty($newEmail) || empty($newPassword)) {
            $error = 'Email et mot de passe sont requis.';
        } else {
            // Mise à jour des informations dans la base de données
            $sql = "UPDATE users SET email = :email, password = :password WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':email', $newEmail, PDO::PARAM_STR);
            $stmt->bindValue(':password', $newPassword, PDO::PARAM_STR);
            $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Mettre à jour la session avec les nouvelles informations
            $_SESSION['email'] = $newEmail;
            $_SESSION['user']['isLoggedIn'] = false; // Déconnexion immédiate

            // Message de succès
            $success = 'Vos informations ont été mises à jour avec succès. Vous allez maintenant vous déconnecter.';

            // Déconnecter l'utilisateur en supprimant les données de session
            session_unset();
            session_destroy();

            // Redirection vers la page de connexion pour se reconnecter avec les nouveaux identifiants
            header('Location: login.php');
            exit;
        }
    } catch (PDOException $e) {
        $error = 'Erreur lors de la mise à jour des informations.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Account</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/darkly/bootstrap.min.css">
</head>
<body>
    <div class="container pt-5">
        <h1>Manage Your Account</h1>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <?php if (!$showAccountInfo): ?>
            <!-- Afficher le bouton pour voir les informations -->
            <form method="post">
                <button type="submit" name="showAccountInfo" class="btn btn-info w-100 mb-3">Afficher mes informations</button>
            </form>

            <!-- Formulaire de modification des informations -->
            <form method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">New Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= $_SESSION['email'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                <a href="com.php" class="btn btn-danger w-100 mt-3">Annuler</a>
            </form>
        <?php elseif ($showAccountInfo && isset($user)): ?>
            <!-- Afficher les informations de compte -->
            <div class="mb-3">
                <h3>Account Information</h3>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Password:</strong> <?= htmlspecialchars($user['password']) ?></p>
            </div>

            <!-- Button "Annuler" -->
            <a href="manageAccount.php" class="btn btn-danger w-100 mt-3">Annuler</a>
        <?php endif; ?>
    </div>
</body>
</html>
