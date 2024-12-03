<?php
session_start();
$id = $_GET['id'];

// Supprimer le produit du panier
unset($_SESSION['panier'][$id]);

header('Location: /PROJET/cart.php'); // Redirige vers le panier après suppression
exit;
?>
