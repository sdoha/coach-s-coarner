in<?php
session_start();
include 'includes/db_connection.php';

// Vérification de la session et des droits
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Vérifier si un ID de match est passé dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Aucun match sélectionné pour évaluation.";
    exit();
}

$id_match = intval($_GET['id']);

// Récupérer les informations du match
$stmt_match = $pdo->prepare("SELECT * FROM Matchs WHERE Id_match = ?");
$stmt_match->execute([$id_match]);
$match = $stmt_match->fetch();

if (!$match) {
    echo "Le match sélectionné n'existe pas.";
    exit();
}

// Afficher les joueurs ayant participé au match
$stmt_joueurs = $pdo->prepare("
    SELECT joueur.Id_Joueur, joueur.Nom, joueur.Prenom, feuillematch.Role
    FROM joueur
    INNER JOIN feuillematch ON joueur.Id_Joueur = feuillematch.Id_Joueur
    WHERE feuillematch.Id_match = ?
");
$stmt_joueurs->execute([$id_match]);
$joueurs_participants = $stmt_joueurs->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluation du Match</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 2rem auto;
            max-width: 800px;
            background: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #16697a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 0.8rem;
            text-align: center;
        }

        th {
            background-color: #16697a;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        button {
            padding: 0.5rem 1rem;
            border-radius: 5px;
            background-color: #4caf50;
            color: white;
            cursor: pointer;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Évaluation du Match : <?= htmlspecialchars($match['Nom_equipe_adverse']); ?></h1>
        <form action="sauvegarder_evaluation.php" method="post">
            <input type="hidden" name="id_match" value="<?= htmlspecialchars($id_match); ?>">

            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Rôle</th>
                        <th>Évaluation (1-10)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($joueurs_participants as $joueur): ?>
                        <tr>
                            <td><?= htmlspecialchars($joueur['Nom']); ?></td>
                            <td><?= htmlspecialchars($joueur['Prenom']); ?></td>
                            <td><?= htmlspecialchars($joueur['Role']); ?></td>
                            <td>
                                <input type="number" name="evaluation[<?= $joueur['Id_Joueur']; ?>]" min="1" max="10" required>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit">Enregistrer l'Évaluation</button>
        </form>
    </div>
</body>
</html>
