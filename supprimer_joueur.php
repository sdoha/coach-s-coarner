<?php
session_start();

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Connexion à la base de données
include 'includes/db_connection.php';

// Vérification de l'ID du joueur
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Récupérer l'ID du joueur à supprimer

    try {
        // Début d'une transaction
        $pdo->beginTransaction();

        // Supprimer les références du joueur dans la table feuillematch
        $stmt_feuille = $pdo->prepare("DELETE FROM feuillematch WHERE Id_Joueur = :id");
        $stmt_feuille->execute(['id' => $id]);

        // Supprimer le joueur dans la table joueur
        $stmt_joueur = $pdo->prepare("DELETE FROM joueur WHERE Id_Joueur = :id");
        $stmt_joueur->execute(['id' => $id]);

        // Vérification si un joueur a bien été supprimé
        if ($stmt_joueur->rowCount() > 0) {
            // Valider la transaction
            $pdo->commit();

            // Redirection avec message de succès
            header('Location: Players.php?message=success&action=deleted');
        } else {
            // Annuler la transaction si le joueur n'existe pas
            $pdo->rollBack();
            header('Location: Players.php?message=error&details=Le joueur n\'existe pas.');
        }
        exit();
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();

        // Log de l'erreur pour débogage
        error_log("Erreur lors de la suppression d'un joueur : " . $e->getMessage());

        // Redirection avec un message d'erreur détaillé
        header('Location: Players.php?message=error&details=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Si aucun ID n'est fourni
    header('Location: Players.php?message=error&details=Aucun joueur spécifié.');
    exit();
}
