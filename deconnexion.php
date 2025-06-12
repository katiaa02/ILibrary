<?php
// Démarrer la session
session_start();

// Détruire toutes les données de session
session_destroy();

// Rediriger vers index.php
header("Location: index.php");
exit; // Assurez-vous d'arrêter l'exécution du script après la redirection
?>
