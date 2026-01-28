<?php
session_start();

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Connexion à la base de données
include 'includes/db_connection.php';

try {
    // Vérification de la méthode POST et de l'ID du match
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_match'])) {
        $id_match = intval($_POST['id_match']); // Récupération de l'ID du match

        // Début d'une transaction
        $pdo->beginTransaction();

        // Supprimer les références du match dans `feuillematch`
        $stmt_feuille = $pdo->prepare("DELETE FROM feuillematch WHERE Id_match = :id_match");
        $stmt_feuille->execute(['id_match' => $id_match]);

        // Supprimer le match dans `matchs`
        $stmt_match = $pdo->prepare("DELETE FROM matchs WHERE Id_match = :id_match");
        $stmt_match->execute(['id_match' => $id_match]);

        // Vérification si un match a bien été supprimé
        if ($stmt_match->rowCount() > 0) {
            // Valider la transaction
            $pdo->commit();
            // Redirection avec un message de succès
            header('Location: matches.php?message=success&action=deleted');
            exit();
        } else {
            // Annuler la transaction si aucune ligne n'a été affectée
            $pdo->rollBack();
            header('Location: matches.php?message=error&details=Le match n\'a pas été trouvé ou supprimé.');
            exit();
        }
    } else {
        // Si la requête est invalide
        throw new Exception('Requête invalide ou ID de match manquant.');
    }
} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Log de l'erreur pour le débogage
    error_log("Erreur lors de la suppression d'un match : " . $e->getMessage());

    // Redirection avec un message d'erreur détaillé
    header('Location: matches.php?message=error&details=' . urlencode($e->getMessage()));
    exit();
}
