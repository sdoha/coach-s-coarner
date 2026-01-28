<?php
// Connexion à la base de données et inclusion des fonctions
include 'includes/db_connection.php';
// Inclure la librairie SQL
include 'includes/database.php';

// Vérification de l'ID du match passé en paramètre
if (!isset($_GET['id_match']) || !is_numeric($_GET['id_match'])) {
    die("Identifiant du match invalide.");
}

$id_match = (int) $_GET['id_match'];

// Vérification si le match est à venir
$match = getUpcomingMatchById($pdo, $id_match);
if (!$match) {
    die("Ce match n'est pas à venir ou n'existe pas.");
}

// Récupération des joueurs actifs
$joueurs = getActivePlayers($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $titularisation = $_POST['titulaire'] ?? [];
    $remplacement = $_POST['remplacant'] ?? [];
    $postes = $_POST['poste'] ?? [];

    // Validation des entrées
    if (count($titularisation) + count($remplacement) < 11) {
        echo "Vous devez sélectionner au moins 11 joueurs.";
    } else {
        // Insertion dans la table `participer`
        $pdo->beginTransaction();

        try {
            foreach ($titularisation as $id_joueur) {
                $poste = $postes[$id_joueur] ?? 'Inconnu';
                insertPlayerParticipation($pdo, $id_joueur, $id_match, $poste, 'Titulaire');
            }

            foreach ($remplacement as $id_joueur) {
                $poste = $postes[$id_joueur] ?? 'Inconnu';
                insertPlayerParticipation($pdo, $id_joueur, $id_match, $poste, 'Remplaçant');
            }

            $pdo->commit();
            echo "La feuille de match a été préparée avec succès.";
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Erreur lors de la préparation de la feuille de match : " . $e->getMessage();
        }
    }
}
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
        }
        .container {
            width: 80%;
            margin: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .actions {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Préparer le Match : <?php echo htmlspecialchars($match['Nom_equipe_adverse']); ?></h1>
    <form method="POST">
        <table>
            <thead>
            <tr>
                <th>Sélection</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Poste Préféré</th>
                <th>Rôle</th>
                <th>Poste</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($joueurs as $joueur): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="titulaire[]" value="<?php echo $joueur['Id_Joueur']; ?>"> Titulaire
                        <br>
                        <input type="checkbox" name="remplacant[]" value="<?php echo $joueur['Id_Joueur']; ?>"> Remplaçant
                    </td>
                    <td><?php echo htmlspecialchars($joueur['Nom']); ?></td>
                    <td><?php echo htmlspecialchars($joueur['Prenom']); ?></td>
                    <td><?php echo htmlspecialchars($joueur['Poste_pref']); ?></td>
                    <td>
                        <select name="poste[<?php echo $joueur['Id_Joueur']; ?>]">
                            <option value="Gardien">Gardien</option>
                            <option value="Défenseur">Défenseur</option>
                            <option value="Milieu">Milieu</option>
                            <option value="Attaquant">Attaquant</option>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="actions">
            <button type="submit">Enregistrer la feuille de match</button>
        </div>
    </form>
</div>
</body>
</html>
