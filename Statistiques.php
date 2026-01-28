<?php
session_start();
include 'includes/db_connection.php';
include 'includes/database.php'; // Inclut les fonctions SQL

// Récupération des statistiques
$stats_globales = getGlobalStats($pdo);
$stats_joueurs = getPlayerStats($pdo);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques</title>
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
            max-width: 1200px;
            background: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color:rgb(22, 122, 39);
        }

        .progress-container {
            margin: 2rem auto;
            width: 90%;
        }

        .progress-bar {
            margin-bottom: 1rem;
        }

        .progress-bar-label {
            font-weight: bold;
            color: #333;
        }

        .progress {
            height: 20px;
            background-color: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            text-align: center;
            line-height: 20px;
            color: #fff;
            border-radius: 10px;
        }

        .progress-fill.victories {
            background-color: #4caf50;
            width: <?= round(100 * $stats_globales['victoires'] / $stats_globales['total'], 2); ?>%;
        }

        .progress-fill.defeats {
            background-color: #f44336;
            width: <?= round(100 * $stats_globales['defaites'] / $stats_globales['total'], 2); ?>%;
        }

        .progress-fill.draws {
            background-color: #ffeb3b;
            color: #000;
            width: <?= round(100 * $stats_globales['nuls'] / $stats_globales['total'], 2); ?>%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 0.8rem;
            text-align: center;
        }

        thead {
            background-color: #16697a;
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #eaf4f4;
        }
        .table-container {
    max-height: 400px; /* Ajustez la hauteur selon vos besoins */
    overflow-y: auto;
    border: 1px solid #ddd; /* Ajoutez une bordure pour une meilleure séparation */
    margin-top: 1rem;
}

    </style>
</head>
<body>
    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h1>Statistiques</h1>

        <!-- Graphique des statistiques globales -->
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-bar-label">Victoires (<?= $stats_globales['victoires']; ?>)</div>
                <div class="progress">
                    <div class="progress-fill victories"> <?= round(100 * $stats_globales['victoires'] / $stats_globales['total'], 2); ?>%</div>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-bar-label">Défaites (<?= $stats_globales['defaites']; ?>)</div>
                <div class="progress">
                    <div class="progress-fill defeats"> <?= round(100 * $stats_globales['defaites'] / $stats_globales['total'], 2); ?>%</div>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-bar-label">Matchs Nuls (<?= $stats_globales['nuls']; ?>)</div>
                <div class="progress">
                    <div class="progress-fill draws"> <?= round(100 * $stats_globales['nuls'] / $stats_globales['total'], 2); ?>%</div>
                </div>
            </div>
        </div>

        <!-- Statistiques par joueur -->
        <h2>Statistiques des joueurs</h2>
        <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Statut</th>
                <th>Poste Préféré</th>
                <th>Titularisations</th>
                <th>Remplacements</th>
                <th>Moyenne Évaluation</th>
                <th>Matchs Joués</th>
                <th>% Victoires</th>
            </tr>
        </thead>
        <tbody>
<?php foreach ($stats_joueurs as $joueur): ?>
    <tr>
        <td><?= htmlspecialchars($joueur['Nom'] ?? ''); ?></td>
        <td><?= htmlspecialchars($joueur['Prenom'] ?? ''); ?></td>
        <td><?= htmlspecialchars($joueur['Statut'] ?? ''); ?></td>
        <td><?= htmlspecialchars($joueur['Poste_pref'] ?? ''); ?></td>
        <td><?= $joueur['titularisations'] ?? 0; ?></td>
        <td><?= $joueur['remplacements'] ?? 0; ?></td>
        <td><?= isset($joueur['moyenne_evaluation']) ? round($joueur['moyenne_evaluation'], 2) : '-'; ?></td>
        <td><?= $joueur['matchs_joues'] ?? 0; ?></td>
        <td><?= $joueur['pourcentage_victoires'] ?? '0'; ?>%</td>
    </tr>
<?php endforeach; ?>
</tbody>


    </table>
</div>
</div>

    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>
