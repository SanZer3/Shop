<?php
session_start(); // Démarre la session
session_unset(); // Supprime toutes les variables de session
session_destroy(); // Détruit la session

// Redirige l'utilisateur vers la page de connexion
header('Location: login.php');
exit();
