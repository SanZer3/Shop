<?php
session_start();

// Vérifiez si les paramètres 'id' et 'qte' sont passés par l'URL
if (isset($_GET['id']) && isset($_GET['qte'])) {
    $id = (int) $_GET['id'];       // ID du produit
    $qte = (int) $_GET['qte'];     // Quantité du produit (convertie en entier)

    // Vérifiez si le panier existe dans la session, sinon l'initialiser
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    // Ajouter le produit au panier (ou le mettre à jour s'il existe déjà)
    if (isset($_SESSION['panier'][$id])) {
        // Si le produit est déjà dans le panier, incrémenter la quantité
        $_SESSION['panier'][$id] += $qte;
    } else {
        // Si le produit n'est pas dans le panier, l'ajouter avec sa quantité
        $_SESSION['panier'][$id] = $qte;
    }

    // Redirection vers la page des produits après l'ajout
    header("Location: com.php");
    exit;
} else {
    // Si les paramètres sont manquants, affichez un message d'erreur
    echo "Erreur : ID ou quantité manquants.";
}
