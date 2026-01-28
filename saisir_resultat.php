<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

include 'includes/db_connection.php';
include 'includes/database.php';

if (isset($_GET['id'])) {
    $id_match = intval($_GET['id']);

    // Récupération des informations du match
    $match = getMatchDetails($pdo, $id_match);

    if (!$match) {
        header('Location: matches.php?message=error');
        exit();
    }

    // Mise à jour du score
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $score_equipe = $_POST['score_equipe'];
        $score_adv = $_POST['score_equipe_adv'];

        if (updateMatchScore($pdo, $id_match, $score_equipe, $score_adv)) {
            header('Location: matches.php?message=success');
            exit();
        } else {
            $error = "Erreur lors de la mise à jour du score.";
        }
    }
} else {
    header('Location: matches.php?message=error');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisir Résultat</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: #f4f4f9;
        }

        .container {
            width: 50%;
            margin: 3rem auto;
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #16697a;
            margin-bottom: 1rem;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        input[type="number"] {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        button {
            padding: 0.7rem;
            background-color: #16697a;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #125d6b;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h2>Saisir Résultat</h2>
        <?php if (!empty($error)): ?>
            <p style="color: red; text-align: center;"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="score_equipe">Score Équipe :</label>
            <input type="number" id="score_equipe" name="score_equipe" value="<?= htmlspecialchars($match['Score_equipe'] ?? 0) ?>" required>

            <label for="score_adv">Score Adversaire :</label>
            <input type="number" id="score_adv" name="score_equipe_adv" value="<?= htmlspecialchars($match['Score_equipe_adv'] ?? 0) ?>" required>

            <button type="submit">Enregistrer</button>
        </form>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
