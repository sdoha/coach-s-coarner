<?php
session_start();

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Connexion à la base de données
include 'includes/db_connection.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Joueurs</title>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        nav .logo img {
            height: 80px;
        }
        nav ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        nav ul li {
            margin: 0 1rem;
        }
        nav ul li a {
            text-decoration: none;
            color: #000;
            font-weight: 600;
        }
        nav ul li a.active {
            color: #ffffff;
            background-color: rgba(29, 128, 40, 0.73);
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        nav .nav-buttons a {
            text-decoration: none;
            padding: 0.5rem 1rem;
            border: 1px solid #000;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        nav .nav-buttons a.logout {
            background-color: #000;
            color: #fff;
        }
        .players-table {
            margin: 2rem auto;
            width: 90%;
            background-color: #ffffff;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .players-table h2 {
            text-align: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .table-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead tr {
            background-color: rgb(34, 114, 28);
            color: #fff;
        }
        th, td {
            padding: 0.8rem;
            text-align: left;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .actions {
    display: flex;
    justify-content: center; /* Centrer horizontalement les boutons */
    gap: 1rem; /* Espacement entre les boutons */
}

.btn-action {
    text-decoration: none;
    padding: 0.6rem 1rem; /* Augmentation de la taille pour une meilleure visibilité */
    border-radius: 5px;
    font-size: 0.9rem; /* Taille légèrement plus grande */
    font-weight: bold;
    color: #fff;
    background-color: #4caf50;
    transition: background-color 0.3s ease;
}

.btn-action:hover {
    background-color: #45a049;
}

.btn-danger {
    background-color: #f44336;
}

.btn-danger:hover {
    background-color: #e53935;
}

        .add-player-form {
            margin: 2rem auto;
            width: 90%;
            max-width: 600px;
            background: linear-gradient(135deg, rgb(11, 84, 31), rgb(178, 230, 182));
            color: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        .add-player-form h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            font-weight: bold;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
        }
        .add-player-form form {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }
        .add-player-form label {
            font-size: 1rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        .add-player-form input, .add-player-form select, .add-player-form textarea {
            padding: 0.8rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .add-player-form input:focus,
        .add-player-form select:focus,
        .add-player-form textarea:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.6);
        }
        .add-player-form button {
            padding: 0.8rem 1rem;
            border: none;
            border-radius: 10px;
            background-color: rgb(16, 73, 3);
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .add-player-form button:hover {
            background-color: #14596a;
        }
    </style>
</head>
<body>
    <!-- Menu de navigation -->
    <nav>
        <div class="logo">
            <img src="assests/img/logo.png" alt="Logo">
        </div>
        <ul>
            <li><a href="Dashboard.php">Accueil</a></li>
            <li><a href="Players.php" class="active">Gestion des joueurs</a></li>
            <li><a href="matches.php">Gestion des matchs</a></li>
            <li><a href="Statistiques.php">Statistiques</a></li>
        </ul>
        <div class="nav-buttons">
            <a href="logout.php" class="logout">Déconnexion</a>
        </div>
    </nav>
   <!-- Affichage des messages -->
<?php if (!empty($_GET['message'])): ?>
    <div style="padding: 1rem; margin: 1rem 0; border-radius: 5px; text-align: center;
        <?php if ($_GET['message'] === 'success'): ?>
            background-color: #4caf50; color: white;
        <?php else: ?>
            background-color: #f44336; color: white;
        <?php endif; ?>">
        <?php if ($_GET['message'] === 'success' && !empty($_GET['action'])): ?>
            <?php if ($_GET['action'] === 'added'): ?>
                Le joueur a été ajouté avec succès.
            <?php elseif ($_GET['action'] === 'updated'): ?>
                Le joueur a été modifié avec succès.
            <?php elseif ($_GET['action'] === 'deleted'): ?>
                Le joueur a été supprimé avec succès.
            <?php else: ?>
                Action réussie.
            <?php endif; ?>
        <?php elseif ($_GET['message'] === 'error'): ?>
            Une erreur est survenue lors de l'action.
            <?php if (!empty($_GET['details'])): ?>
                (<?= htmlspecialchars($_GET['details']); ?>)
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>


    <!-- Tableau des joueurs -->
    <div class="players-table">
        <h2>Liste des Joueurs</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Numéro de Licence</th>
                        <th>Date de Naissance</th>
                        <th>Taille (cm)</th>
                        <th>Poids (kg)</th>
                        <th>Statut</th>
                        <th>Commentaire</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM joueur");
                    while ($row = $stmt->fetch()) {
                        echo "<tr>
                            <td>" . htmlspecialchars($row['Nom']) . "</td>
                            <td>" . htmlspecialchars($row['Prenom']) . "</td>
                            <td>" . htmlspecialchars($row['Num_licence']) . "</td>
                            <td>" . htmlspecialchars($row['Date_naissance']) . "</td>
                            <td>" . htmlspecialchars($row['Taille']) . "</td>
                            <td>" . htmlspecialchars($row['Poids']) . "</td>
                            <td>" . htmlspecialchars($row['Statut']) . "</td>
                            <td>" . htmlspecialchars($row['Commentaire']) . "</td>
                            <td class='actions'>
                                <a href='modifier_joueur.php?id=" . htmlspecialchars($row['Id_Joueur']) . "' class='btn-action'>Modifier</a>
                                <a href='supprimer_joueur.php?id=" . htmlspecialchars($row['Id_Joueur']) . "' class='btn-action btn-danger'>Supprimer</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Formulaire pour ajouter un joueur -->
    <div class="add-player-form">
        <h2>Ajouter un Nouveau Joueur</h2>
        <form action="ajouter_joueur.php" method="post">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="Nom" placeholder="Entrez le nom" required>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="Prenom" placeholder="Entrez le prénom" required>

            <label for="numero_licence">Numéro de Licence :</label>
            <input type="text" id="numero_licence" name="Num_licence" placeholder="Entrez le numéro de licence" required>

            <label for="date_naissance">Date de Naissance :</label>
            <input type="date" id="date_naissance" name="Date_naissance" required>

            <label for="taille">Taille (cm) :</label>
            <input type="number" id="taille" name="Taille" placeholder="Ex : 180" required>

            <label for="poids">Poids (kg) :</label>
            <input type="number" id="poids" name="Poids" placeholder="Ex : 75" required>

            <label for="statut">Statut :</label>
            <select id="statut" name="Statut" required>
                <option value="Actif">Actif</option>
                <option value="Blessé">Blessé</option>
                <option value="Suspendu">Suspendu</option>
                <option value="Absent">Absent</option>
            </select>

            <label for="commentaire">Commentaire :</label>
            <textarea id="commentaire" name="Commentaire" rows="4" placeholder="Ajoutez des remarques"></textarea>

            <button type="submit">Ajouter le Joueur</button>
        </form>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>
