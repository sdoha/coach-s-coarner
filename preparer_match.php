<?php
session_start();
include 'includes/db_connection.php';
// Inclure la librairie SQL
include 'includes/database.php';

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Récupérer l'ID du match à préparer
if (!isset($_GET['id_match']) || empty($_GET['id_match'])) {
    echo "Identifiant du match manquant.";
    exit();
}

$id_match = (int) $_GET['id_match'];

// Récupérer les détails du match
$match = getMatchDetailsById($pdo, $id_match);

if (!$match) {
    echo "Match introuvable.";
    exit();
}

// Récupérer les joueurs actifs
$joueurs = getActivePlayers($pdo);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préparer le Match</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #16697a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #16697a;
            color: #fff;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #4caf50;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Préparer le match contre <?= htmlspecialchars($match['Nom_equipe_adverse']); ?></h1>
        <p><strong>Date :</strong> <?= htmlspecialchars($match['Date_match']); ?></p>
        <p><strong>Lieu :</strong> <?= htmlspecialchars($match['Lieu']); ?></p>

        <h2>Liste des joueurs disponibles</h2>
        <form method="post" action="sauvegarder_feuille_match.php">
            <input type="hidden" name="id_match" value="<?= $id_match; ?>">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Poste Préféré</th>
                        <th>Titulaire</th>
                        <th>Remplaçant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($joueurs as $joueur): ?>
                        <tr>
                            <td><?= htmlspecialchars($joueur['Nom']); ?></td>
                            <td><?= htmlspecialchars($joueur['Prenom']); ?></td>
                            <td><?= htmlspecialchars($joueur['Poste_pref']); ?></td>
                            <td>
                                <input type="radio" name="role[<?= $joueur['Id_Joueur']; ?>]" value="Titulaire">
                            </td>
                            <td>
                                <input type="radio" name="role[<?= $joueur['Id_Joueur']; ?>]" value="Remplaçant">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <button type="submit" class="btn">Valider la Feuille de Match</button>
        </form>
    </div>
</body>
</html>
