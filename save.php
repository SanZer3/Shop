<?php
session_start();
try {
    // Récupération des données POST
    $id = $_POST['idd'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $role = $_POST['role'];

    // Connexion à la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL pour mettre à jour l'utilisateur
    $sql = "UPDATE users SET email = :email, password = :pass, role = :role WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // Binding des paramètres
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
    $stmt->bindValue(':role', $role, PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    // Exécution de la requête
    $stmt->execute();

    // Redirection vers la page principale en cas de succès
    $header = "Location: /Projet/index.php";
} catch (PDOException $e) {
    // Affichage de l'erreur pour le débogage
    echo "Erreur : " . $e->getMessage();
    exit;
}

// Redirection
header($header);
exit;
?>
