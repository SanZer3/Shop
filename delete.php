<?php
session_start();
try {
    // Récupérer l'ID de l'utilisateur depuis l'URL
    if (!isset($_GET['idd']) || !is_numeric($_GET['idd'])) {
        throw new Exception("ID invalide ou manquant.");
    }

    $id = (int) $_GET['idd']; // Convertir en entier pour éviter toute injection

    // Connexion à la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=db', 'root', '');

    // Préparer et exécuter la requête de suppression
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // Redirection après suppression
    header("Location: /Projet/index.php"); // Vérifiez que le chemin est correct
} catch (Exception $e) {
    // Redirection en cas d'erreur avec un message
    header("Location: /Projet/index.php?msg=" . urlencode($e->getMessage()));
}

// Arrêter l'exécution après la redirection
exit;
?>
