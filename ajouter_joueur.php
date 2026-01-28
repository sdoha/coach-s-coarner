<?php
session_start();

// Vérifier la connexion à la base de données
include 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['Nom'];
    $prenom = $_POST['Prenom'];
    $num_licence = $_POST['Num_licence'];
    $date_naissance = $_POST['Date_naissance'];
    $taille = $_POST['Taille'];
    $poids = $_POST['Poids'];
    $statut = $_POST['Statut'];
    $commentaire = $_POST['Commentaire'];

    try {
        // Appel de la fonction pour ajouter un joueur
        ajouterJoueur($pdo, $nom, $prenom, $num_licence, $date_naissance, $taille, $poids, $statut, $commentaire);

        // Redirection avec message de succès
        header('Location: Players.php?message=success&action=added');        exit();
    } catch (Exception $e) {
        // Redirection avec message d'erreur
        header('Location: Players.php?message=error');
        exit();
    }
} else {
    // Redirection si la méthode n'est pas POST
    header('Location: Players.php');
    exit();
}
?>
