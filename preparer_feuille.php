<?php
session_start();
include 'includes/db_connection.php';
// Inclure la librairie SQL
include 'includes/database.php';

// Vérifier si un match est sélectionné
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Aucun match sélectionné.";
    exit();
}

$id_match = intval($_GET['id']);

// Vérifier si le match existe
$match = getMatchById($pdo, $id_match);
if (!$match) {
    echo "Le match sélectionné n'existe pas.";
    exit();
}

// Récupérer les joueurs déjà sélectionnés pour ce match et leurs rôles
$joueurs_selectionnes = getSelectedPlayers($pdo, $id_match);

// Transformer en un tableau avec Id_Joueur comme clé et le rôle comme valeur
$joueurs_roles = [];
foreach ($joueurs_selectionnes as $selection) {
    $joueurs_roles[$selection['Id_Joueur']] = $selection['Role'];
}

// Récupérer les joueurs actifs avec les informations supplémentaires
$joueurs_actifs = getActivePlayersWithDetails($pdo);

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préparer Feuille de Match</title>
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
            max-width: 1000px;
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

        tr:hover {
            background-color: #eaf4f4;
        }

        select, button {
            padding: 0.5rem;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        button {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Préparer le Match : <?= htmlspecialchars($match['Nom_equipe_adverse']); ?></h1>
        <form action="sauvegarder_feuille.php" method="post">
            <input type="hidden" name="id_match" value="<?= htmlspecialchars($id_match); ?>">
            
            <!-- Tableau des joueurs -->
            <table>
                <thead>
                    <tr>
                        <th>Sélectionner</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Poste Préféré</th>
                        <th>Taille (cm)</th>
                        <th>Poids (kg)</th>
                        <th>Commentaires</th>
                        <th>Rôle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($joueurs_actifs as $joueur): ?>
                        <tr>
                            <td>
                                <!-- Case à cocher avec vérification -->
                                <input type="checkbox" name="joueurs[]" value="<?= $joueur['Id_Joueur']; ?>"
                                    <?= array_key_exists($joueur['Id_Joueur'], $joueurs_roles) ? 'checked' : ''; ?>>
                            </td>
                            <td><?= htmlspecialchars($joueur['Nom'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?= htmlspecialchars($joueur['Prenom'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?= htmlspecialchars($joueur['Poste_pref'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?= htmlspecialchars($joueur['Taille'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?= htmlspecialchars($joueur['Poids'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?= htmlspecialchars($joueur['Commentaire'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>

                            <td>
                                <!-- Sélection du rôle avec valeur par défaut -->
                                <select name="type_joueur[<?= $joueur['Id_Joueur']; ?>]">
                                    <option value="titulaire" <?= (array_key_exists($joueur['Id_Joueur'], $joueurs_roles) && $joueurs_roles[$joueur['Id_Joueur']] === 'titulaire') ? 'selected' : ''; ?>>Titulaire</option>
                                    <option value="remplaçant" <?= (array_key_exists($joueur['Id_Joueur'], $joueurs_roles) && $joueurs_roles[$joueur['Id_Joueur']] === 'remplaçant') ? 'selected' : ''; ?>>Remplaçant</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit">Valider la Feuille de Match</button>
        </form>
    </div>
</body>
</html>
