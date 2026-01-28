<?php
session_start();

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Activer l'affichage des erreurs en développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
include 'includes/db_connection.php';

// Initialisation du message de confirmation
$message = "";

// Initialisation de la variable $id_match
$id_match = null;

// Mise à jour du résultat d'un match
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_match'])) {
    $id_match = intval($_POST['id_match']);
    $resultat = $_POST['resultat'];
    $score_equipe = intval($_POST['score_equipe']);
    $score_equipe_adv = intval($_POST['score_equipe_adv']);

    try {
        $stmt_update = $pdo->prepare("UPDATE matchs SET Resultat = :resultat, Score_equipe = :score_equipe, Score_equipe_adv = :score_equipe_adv WHERE Id_match = :id_match");
        $stmt_update->execute([
            'resultat' => $resultat,
            'score_equipe' => $score_equipe,
            'score_equipe_adv' => $score_equipe_adv,
            'id_match' => $id_match,
        ]);

        // Définir un message de confirmation
        $message = "Le match a été mis à jour avec succès.";
    } catch (Exception $e) {
        $message = "Erreur lors de la mise à jour : " . $e->getMessage();
    }
}

// Vérifier que $id_match est défini avant l'UPDATE
if ($id_match !== null) {
    try {
        $stmt = $pdo->prepare("UPDATE matchs SET StatutPreparer = 1 WHERE Id_match = ?");
        $stmt->execute([$id_match]);
    } catch (Exception $e) {
        $message = "Erreur lors de la mise à jour du statut : " . $e->getMessage();
    }
}
// Suppression d'un match
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_match'])) {
    $id_match_to_delete = intval($_POST['delete_match']);
    try {
        $stmt_delete = $pdo->prepare("DELETE FROM matchs WHERE Id_match = ?");
        $stmt_delete->execute([$id_match_to_delete]);

        // Ajouter un message de confirmation
        $message = "Le match a été supprimé avec succès.";
    } catch (Exception $e) {
        $message = "Erreur lors de la suppression : " . $e->getMessage();
    }
}

// Obtenir les matchs à venir
$stmt_upcoming = $pdo->query("SELECT * FROM matchs WHERE CONCAT(Date_match, ' ', Heure) > NOW() ORDER BY Date_match ASC");
$matchs_avenir = $stmt_upcoming->fetchAll();

// Obtenir les matchs terminés
$stmt_past = $pdo->query("SELECT * FROM matchs WHERE CONCAT(Date_match, ' ', Heure) <= NOW() ORDER BY Date_match DESC");
$matchs_termines = $stmt_past->fetchAll();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Matchs</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
        }

        .matches-container {
            margin: 2rem auto;
            width: 90%;
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .matches-container h2 {
    text-align: center; /* Centre le texte horizontalement */
    font-size: 2rem; /* Augmente la taille pour une meilleure visibilité */
    margin: 1rem 0; /* Ajoute un espacement propre au-dessus et en dessous */
    color: #16697a; /* Couleur uniforme */
    font-weight: bold; /* Met en gras pour mettre en évidence */
    text-transform: uppercase; /* Convertit en majuscules pour un style uniforme */
    letter-spacing: 0.05em; /* Ajoute un léger espacement entre les lettres */
}


        .message {
            padding: 1rem;
            background-color: #4caf50;
            color: white;
            text-align: center;
            margin-bottom: 1rem;
            border-radius: 5px;
        }

        .add-match-form {
            margin: 2rem auto;
            width: 90%;
            max-width: 600px;
            background: linear-gradient(135deg, #16697a, #82c4d8);
            color: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .add-match-form h3 {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: bold;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
        }

        .add-match-form form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .add-match-form label {
            font-size: 1rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .add-match-form input, .add-match-form textarea {
            padding: 0.8rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .add-match-form input:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.6);
        }

        .add-match-form button {
            padding: 0.8rem 1rem;
            border: none;
            border-radius: 10px;
            background-color: #125d6b;
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-match-form button:hover {
            background-color:rgb(15, 75, 88);
        }

        .table-container {
            max-height: 400px;
            overflow-y: auto;
            margin: 1rem 0;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background-color:rgb(22, 122, 50);
            color: #ffffff;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .actions a {
            text-decoration: none;
            margin-right: 10px;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            color: #ffffff;
            font-weight: bold;
            background-color: #16697a;
            transition: background-color 0.3s ease;
        }

        .actions a:hover {
            background-color: #125d6b;
        }

        .actions a.result {
            background-color: #4caf50;
        }

        .actions a.result:hover {
            background-color: #45a049;
        }

        .actions a.evaluate {
            background-color: #ff9800;
        }

        .actions a.evaluate:hover {
            background-color: #e68a00;
        }

        .update-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .update-form input[type="number"], .update-form select {
            padding: 0.5rem;
            font-size: 1rem;
        }

        .update-form button {
            padding: 0.5rem 1rem;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .update-form button:hover {
            background-color: #45a049;
        }
       .btn {
        padding: 0.5rem 1rem;
        border-radius: 5px;
        font-weight: bold;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background-color:rgb(0, 123, 255); /* Bleu pour "Préparer" */
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-success {
        background-color: #28a745; /* Vert pour "Préparé" */
        color: white;
        border: none;
    }

    .btn-success:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Nouveau style pour le bouton "Préparé" */
    .btn-prepared {
        background-color: #28a745; /* Vert clair */
        color: white;
        border: none;
        padding: 0.4rem 0.8rem; /* Plus petit que "Préparer" */
        font-size: 0.9rem; /* Réduction de la taille du texte */
        border-radius: 5px;
        cursor: default;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-prepared:hover {
        background-color: #28a745; /* Pas de changement au survol */
    }

    /* Nouveau style pour le bouton "Supprimer" */
    .btn-danger {
        background-color: #dc3545; /* Rouge pour "Supprimer" */
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }
</style>
    </style>
</head>
<body>
    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Afficher les messages de confirmation ou d'erreur -->
    <?php if (!empty($_GET['message'])): ?>
        <div class="message" style="padding: 1rem; background-color: #4caf50; color: white; text-align: center; margin-bottom: 1rem; border-radius: 5px;">
            <?= htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_GET['error'])): ?>
        <div class="message" style="padding: 1rem; background-color: #f44336; color: white; text-align: center; margin-bottom: 1rem; border-radius: 5px;">
            <?= htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="matches-container">
        <h2>Matchs à venir</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Lieu</th>
                    <th>Équipe Adverse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php if (count($matchs_avenir) > 0): ?>
        <?php foreach ($matchs_avenir as $match): ?>
            <tr>
                <td><?= htmlspecialchars($match['Date_match'] ?? '') ?></td>
                <td><?= htmlspecialchars($match['Heure'] ?? '') ?></td>
                <td><?= htmlspecialchars($match['Lieu'] ?? '') ?></td>
                <td><?= htmlspecialchars($match['Nom_equipe_adverse'] ?? '') ?></td>
                <td class="actions">
    <?php if (!empty($match['StatutPreparer']) && $match['StatutPreparer'] == 1): ?>
        <!-- Bouton "Préparé" -->
        <a href="preparer_feuille.php?id=<?= htmlspecialchars($match['Id_match']); ?>" class="btn btn-success">Préparé</a>
    <?php else: ?>
        <!-- Bouton "Préparer" -->
        <a href="preparer_feuille.php?id=<?= htmlspecialchars($match['Id_match']); ?>" class="btn btn-primary">Préparer</a>
    <?php endif; ?>
    <!-- Bouton "Supprimer" -->
    <form method="POST" action="supprimer_match.php" style="display:inline;">
        <input type="hidden" name="id_match" value="<?= htmlspecialchars($match['Id_match']); ?>">
        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce match ?')">Supprimer</button>
    </form>
</td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" style="text-align: center;">Aucun match à venir.</td>
        </tr>
    <?php endif; ?>
</tbody>
        </table>

        <h2>Matchs terminés</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Lieu</th>
                        <th>Équipe Adverse</th>
                        <th>Score</th>
                        <th>Modifier</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($matchs_termines) > 0): ?>
                        <?php foreach ($matchs_termines as $match): ?>
                            <tr>
                                <td><?= htmlspecialchars($match['Date_match']) ?></td>
                                <td><?= htmlspecialchars($match['Heure']) ?></td>
                                <td><?= htmlspecialchars($match['Lieu']) ?></td>
                                <td><?= htmlspecialchars($match['Nom_equipe_adverse']) ?></td>
                                <td>
                                    <?= htmlspecialchars($match['Score_equipe'] ?? '-') ?> - 
                                    <?= htmlspecialchars($match['Score_equipe_adv'] ?? '-') ?>
                                </td>
                                <td>
                                    <form method="POST" class="update-form">
                                        <input type="hidden" name="id_match" value="<?= htmlspecialchars($match['Id_match']) ?>">
                                        <input type="number" name="score_equipe" placeholder="Score équipe" value="<?= htmlspecialchars($match['Score_equipe']) ?>" required>
                                        <input type="number" name="score_equipe_adv" placeholder="Score adversaire" value="<?= htmlspecialchars($match['Score_equipe_adv']) ?>" required>
                                        <select name="resultat" required>
                                            <option value="Gagné" <?= $match['Resultat'] === 'Gagné' ? 'selected' : '' ?>>Gagné</option>
                                            <option value="Perdu" <?= $match['Resultat'] === 'Perdu' ? 'selected' : '' ?>>Perdu</option>
                                            <option value="Nul" <?= $match['Resultat'] === 'Nul' ? 'selected' : '' ?>>Nul</option>
                                        </select>
                                        <button type="submit" name="update_match">Mettre à jour</button>
                                    </form>
                                </td>
                                <td class="actions">
                                    <a href="evaluer_match.php?id=<?= htmlspecialchars($match['Id_match']); ?>" class="evaluate">Évaluer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">Aucun match terminé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
   <!-- Formulaire pour ajouter un nouveau match -->
    <div class="add-match-form">
        <h3>Ajouter un Nouveau Match</h3>
        <form action="ajouter_match.php" method="post">
            <label for="date_match">Date du Match :</label>
            <input type="date" id="date_match" name="date_match" required>

            <label for="heure">Heure :</label>
            <input type="time" id="heure" name="heure" required>

            <label for="lieu">Lieu :</label>
            <input type="text" id="lieu" name="lieu" placeholder="Lieu du match" required>

            <label for="nom_equipe_adverse">Nom de l'Équipe Adverse :</label>
            <input type="text" id="nom_equipe_adverse" name="nom_equipe_adverse" placeholder="Équipe adverse" required>

            <button type="submit">Ajouter le Match</button>
        </form>
    </div>

    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>