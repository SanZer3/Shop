<?php
try {
    // Validation des données
    if (empty($_POST['email']) || empty($_POST['pass']) || empty($_POST['role'])) {
        header('Location: /Projet/index.php?msg=Tous+les+champs+sont+obligatoires');
        exit;
    }

    $email = $_POST['email'];
    $pass = $_POST['pass']; // Mot de passe non haché
    $role = $_POST['role'];

    $pdo = new PDO('mysql:host=localhost;dbname=db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérification si l'e-mail existe déjà
    $checkEmailSql = "SELECT COUNT(*) FROM users WHERE email = ?";
    $checkStmt = $pdo->prepare($checkEmailSql);
    $checkStmt->bindValue(1, $email, PDO::PARAM_STR);
    $checkStmt->execute();
    $emailExists = $checkStmt->fetchColumn();

    if ($emailExists > 0) {
        // Si l'e-mail existe, afficher un message d'erreur
        header('Location: /Projet/index.php?msg=Cet+email+est+déjà+utilisé');
        exit;
    }

    // Si l'e-mail n'existe pas, insérer les données
    $sql = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $email, PDO::PARAM_STR);
    $stmt->bindValue(2, $pass, PDO::PARAM_STR);
    $stmt->bindValue(3, $role, PDO::PARAM_STR);
    $stmt->execute();

    header('Location: /Projet/index.php?msg=Utilisateur+ajouté+avec+succès');
    exit;
} catch (PDOException $e) {
    header('Location: /Projet/index.php?msg=Erreur+d\'ajout');
    exit;
}
