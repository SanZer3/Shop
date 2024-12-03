<?php
session_start();

// Vérifiez si les données nécessaires sont présentes
if (isset($_POST['id']) && isset($_POST['quantity'])) {
    $id = $_POST['id'];
    $quantity = (int) $_POST['quantity']; // Convertir la quantité en entier

    // Vérifiez que la quantité est valide
    if ($quantity > 0) {
        // Mettre à jour la quantité dans la session
        $_SESSION['panier'][$id] = $quantity;
    } else {
        // Si la quantité est invalide, retirer l'article du panier
        unset($_SESSION['panier'][$id]);
    }
}

// Rediriger vers la page du panier après la mise à jour
header("Location: cart.php");
exit;
