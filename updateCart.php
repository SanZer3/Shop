<?php
session_start();

$id = $_GET['id'];
$qte = $_GET['qte'];

// Vérifiez si la quantité est valide
if ($qte > 0) {
    $_SESSION['panier'][$id] = $qte; // Met à jour la quantité
} else {
    unset($_SESSION['panier'][$id]); // Si la quantité est 0 ou inférieure, on retire l'article du panier
}

header('Location: /cart.php'); // Redirige vers la page du panier après mise à jour
exit;
?>
