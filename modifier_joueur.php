<?php
session_start();

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure les connexions et fonctions
include 'includes/db_connection.php';
// Inclure la librairie SQL
include 'includes/database.php';

// Vérifier l'identifiant du joueur
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Identifiant du joueur invalide.";
    exit();
}

$id = intval($_GET['id']);

// Récupération des données du joueur
$joueur = getPlayerById($pdo, $id);

if (!$joueur) {
    echo "Joueur introuvable.";
    exit();
}

// Mise à jour des données
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['Nom'];
    $prenom = $_POST['Prenom'];
    $num_licence = $_POST['Num_licence'];
    $date_naissance = $_POST['Date_naissance'];
    $taille = $_POST['Taille'];
    $poids = $_POST['Poids'];
    $statut = $_POST['Statut'];
    $commentaire = $_POST['Commentaire'];

    if (updatePlayer($pdo, $id, $nom, $prenom, $num_licence, $date_naissance, $taille, $poids, $statut, $commentaire)) {
        header('Location: Players.php?message=success&action=updated');
            } else {
        echo "Erreur lors de la mise à jour du joueur.";
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Joueur</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            padding: 0;
        }

        .edit-player-form {
            max-width: 600px;
            margin: 3rem auto;
            padding: 2rem;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .edit-player-form h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #16697a;
        }

        .edit-player-form label {
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 0.5rem;
        }

        .edit-player-form input,
        .edit-player-form select,
        .edit-player-form textarea {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .edit-player-form input:focus,
        .edit-player-form select:focus,
        .edit-player-form textarea:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .edit-player-form button {
            width: 100%;
            padding: 1rem;
            background-color: #16697a;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .edit-player-form button:hover {
            background-color: #125d6b;
        }

        .back-link {
            text-align: center;
            margin-top: 1rem;
        }

        .back-link a {
            text-decoration: none;
            color: #16697a;
            font-weight: bold;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="edit-player-form">
        <h2>Modifier un Joueur</h2>
        <form action="" method="post">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="Nom" value="<?= htmlspecialchars($joueur['Nom']) ?>" required>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="Prenom" value="<?= htmlspecialchars($joueur['Prenom']) ?>" required>

            <label for="numero_licence">Numéro de Licence :</label>
            <input type="text" id="numero_licence" name="Num_licence" value="<?= htmlspecialchars($joueur['Num_licence']) ?>" required>

            <label for="date_naissance">Date de Naissance :</label>
            <input type="date" id="date_naissance" name="Date_naissance" value="<?= htmlspecialchars($joueur['Date_naissance']) ?>" required>

            <label for="taille">Taille (cm) :</label>
            <input type="number" id="taille" name="Taille" value="<?= htmlspecialchars($joueur['Taille']) ?>" required>

            <label for="poids">Poids (kg) :</label>
            <input type="number" id="poids" name="Poids" value="<?= htmlspecialchars($joueur['Poids']) ?>" required>

            <label for="statut">Statut :</label>
            <select id="statut" name="Statut" required>
                <option value="Actif" <?= $joueur['Statut'] == 'Actif' ? 'selected' : '' ?>>Actif</option>
                <option value="Blessé" <?= $joueur['Statut'] == 'Blessé' ? 'selected' : '' ?>>Blessé</option>
                <option value="Suspendu" <?= $joueur['Statut'] == 'Suspendu' ? 'selected' : '' ?>>Suspendu</option>
                <option value="Absent" <?= $joueur['Statut'] == 'Absent' ? 'selected' : '' ?>>Absent</option>
            </select>

            <label for="commentaire">Commentaire :</label>
            <textarea id="commentaire" name="Commentaire" rows="4"><?= htmlspecialchars($joueur['Commentaire']) ?></textarea>

            <button type="submit">Mettre à Jour</button>
        </form>
        <div class="back-link">
            <a href="Players.php">Retour à la liste des joueurs</a>
        </div>
    </div>
</body>
</html>
