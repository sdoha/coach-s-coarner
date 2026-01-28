<?php
session_start();

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Connexion à la base de données
include 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $date_match = $_POST['date_match'];
    $heure = $_POST['heure'];
    $lieu = $_POST['lieu'];
    $nom_equipe_adverse = $_POST['nom_equipe_adverse'];

    try {
        // Appel de la fonction pour ajouter un match
        ajouterMatch($pdo, $date_match, $heure, $lieu, $nom_equipe_adverse);

        // Redirection avec un message de succès
        header('Location: matches.php?message=match_added');
        exit();
    } catch (Exception $e) {
        // Afficher une erreur en cas de problème
        echo $e->getMessage();
    }
} else {
    // Rediriger si l'accès est direct (non POST)
    header('Location: matches.php');
    exit();
}
?>
