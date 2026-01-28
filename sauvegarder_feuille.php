<?php
session_start();
include 'includes/db_connection.php';

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérification de la méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Récupérer l'ID du match
        if (!isset($_POST['id_match']) || empty($_POST['id_match'])) {
            throw new Exception("ID du match manquant ou invalide.");
        }

        $id_match = intval($_POST['id_match']);

        // Vérifier que des joueurs sont sélectionnés
        if (empty($_POST['joueurs']) || !is_array($_POST['joueurs'])) {
            header("Location: preparer_feuille.php?id=$id_match&message=Aucun joueur sélectionné.");
            exit();
        }

        // Démarrer une transaction pour assurer la cohérence des données
        $pdo->beginTransaction();

        // Supprimer les sélections existantes pour ce match
        $stmt_delete = $pdo->prepare("DELETE FROM feuillematch WHERE Id_match = ?");
        $stmt_delete->execute([$id_match]);

        // Insérer les nouveaux joueurs sélectionnés
        $stmt_insert = $pdo->prepare("
            INSERT INTO feuillematch (Id_match, Id_Joueur, Role)
            VALUES (?, ?, ?)
        ");

        foreach ($_POST['joueurs'] as $id_joueur) {
            $role = $_POST['type_joueur'][$id_joueur] ?? 'titulaire'; // Défaut: titulaire
            $stmt_insert->execute([$id_match, intval($id_joueur), htmlspecialchars($role)]);
        }

        // Mettre à jour le statut du match à "Préparé"
        $stmt_update = $pdo->prepare("UPDATE matchs SET StatutPreparer = 1 WHERE Id_match = ?");
        $stmt_update->execute([$id_match]);

        // Commit de la transaction
        $pdo->commit();

        // Redirection vers matches.php avec message de succès
        $_SESSION['message'] = "La feuille de match a été validée avec succès.";
        header("Location: matches.php");
        exit();
    } catch (Exception $e) {
        // Rollback de la transaction en cas d'erreur
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        // Log de l'erreur pour le débogage
        file_put_contents('log.txt', "[" . date('Y-m-d H:i:s') . "] Erreur : " . $e->getMessage() . "\n", FILE_APPEND);

        // Redirection avec message d'erreur
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
        header("Location: preparer_feuille.php?id=$id_match&message=" . urlencode("Erreur : " . $e->getMessage()));
        exit();
    }
} else {
    // Redirection si la requête n'est pas POST
    header("Location: matches.php?message=Requête invalide.");
    exit();
}
