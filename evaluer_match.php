<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

include 'includes/database.php';

if (isset($_GET['id'])) {
    $id_match = intval($_GET['id']);

    try {
        // Récupérer les joueurs ayant participé au match
        $joueurs = getJoueursParMatch($pdo, $id_match);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Mettre à jour les évaluations
            updateEvaluations($pdo, $id_match, $_POST['evaluations']);

            // Redirection avec message de succès
            header('Location: matches.php?message=success');
            exit();
        }
    } catch (Exception $e) {
        // Redirection avec message d'erreur
        header('Location: matches.php?message=error');
        exit();
    }
} else {
    // Redirection si l'identifiant de match n'est pas fourni
    header('Location: matches.php?message=error');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluer les Joueurs</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #16697a;
            color: #ffffff;
        }

        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 5px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 1.5rem;
            color: #ccc;
            cursor: pointer;
        }

        .star-rating input:checked ~ label {
            color: #ffc107;
        }

        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #ffc107;
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
        <h2>Évaluer les Joueurs</h2>
        <?php if (!empty($joueurs)): ?>
            <form method="POST">
                <table>
                    <thead>
                        <tr>
                            <th>Joueur</th>
                            <th>Évaluation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($joueurs as $joueur): ?>
                            <tr>
                                <td><?= htmlspecialchars($joueur['Nom'] . ' ' . $joueur['Prenom']) ?></td>
                                <td>
                                    <div class="star-rating">
                                        <?php for ($i = 5; $i >= 1; $i--): ?>
                                            <input 
                                                type="radio" 
                                                id="star<?= $i ?>-<?= $joueur['Id_Joueur'] ?>" 
                                                name="evaluations[<?= $joueur['Id_Joueur'] ?>]" 
                                                value="<?= $i ?>" 
                                                <?= ($joueur['Evaluation'] == $i) ? 'checked' : '' ?>>
                                            <label for="star<?= $i ?>-<?= $joueur['Id_Joueur'] ?>">&#9733;</label>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit">Enregistrer</button>
            </form>
        <?php else: ?>
            <p>Aucun joueur à évaluer pour ce match.</p>
        <?php endif; ?>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
