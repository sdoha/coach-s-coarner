<?php
session_start();

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Inclure la librairie SQL
include 'includes/database.php';

// Récupérer les données nécessaires
$matches = getUpcomingMatches1($pdo);
$joueurs_blesse = getInjuredPlayers($pdo);
$joueurs_suspendu = getSuspendedPlayers($pdo);
$user = getUserName($pdo, $_SESSION['user_id']);
$stats = getPerformanceStats($pdo);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
        .video-container {
            position: relative; /* Pour positionner le texte sur la vidéo */
            width: 100%;
            overflow: hidden;
        }
        .video-container video {
            width: 100%;
            height: auto;
            display: block;
        }
        .video-container .overlay-text {
            position: absolute;
            top: 50%; /* Centré verticalement */
            left: 50%; /* Centré horizontalement */
            transform: translate(-50%, -50%);
            color: white;
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
            text-align: center;
        }
        .menu {
            display: flex;
            justify-content: center;
            margin: 2rem 0;
        }
        .menu a {
            text-decoration: none;
            margin: 0 1rem;
            padding: 1rem 2rem;
            border: 2px solid #007bff;
            color:rgb(25, 134, 49);
            border-radius: 10px;
            font-weight: bold;
            background-color: white;
            transition: all 0.3s ease;
        }
        .menu a:hover {
            background-color:rgb(25, 130, 51);
            color: white;
        }
        /* Styles pour la modale */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            width: 90%;
            max-width: 400px;
        }

        .modal-content h3 {
            margin-bottom: 1rem;
        }

        .modal-buttons {
            display: flex;
            justify-content: space-around;
            margin-top: 1rem;
        }

        .modal-buttons a {
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .modal-buttons .confirm {
            background: #1D4D04;
            color: white;
        }

        .modal-buttons .cancel {
            background: #ddd;
            color: #000;
        }
        .general-stats {
    text-align: center;
    margin: 2rem auto;
    padding: 1rem;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.stats-cards {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.stats-cards .card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 1rem 2rem;
    flex: 1;
    max-width: 300px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.stats-cards .card h3 {
    margin-bottom: 0.5rem;
    font-size: 1.2rem;
}

.stats-cards .card p {
    font-size: 1rem;
    color: #555;
}
.quick-actions {
    text-align: center;
    margin: 2rem 0;
}

.quick-actions h2 {
    font-size: 2rem;
    margin-bottom: 1.5rem;
}

.actions-container {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.action-card {
    width: 150px;
    text-align: center;
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
}

.action-card img {
    width: 64px;
    height: 64px;
    margin-bottom: 1rem;
}

.action-card a {
    text-decoration: none;
    font-weight: bold;
    color: #006400; /* Changement en vert foncé */
    font-size: 1rem;
    display: block; /* Bloque le texte pour un meilleur alignement */
    margin-top: 0.5rem; /* Ajouté pour un meilleur espacement */
}

.action-card a:hover {
    color:rgb(9, 109, 42);
}


.action-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.action-buttons .button {
    text-decoration: none;
    background-color:rgb(39, 165, 85);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.action-buttons .button:hover {
    background-color:rgb(15, 151, 67);
}
table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
    font-size: 1rem;
    text-align: left;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
}
.centered-title {
    text-align: center;
    font-weight: bold;
    margin: 20px 0;
}


thead tr {
    background-color:rgb(24, 104, 2);
    color: white;
}

th, td {
    padding: 0.8rem;
    border: 1px solid #ddd;
}

tr:nth-child(even) {
    background-color: #f2f2f2;
}

a {
    color: blue;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

.player-focus {
    text-align: center;
    margin: 2rem 0;
}

.player-focus h2 {
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 1.5rem;
    color: #333;
}

.focus-container {
    display: flex;
    justify-content: center;
    gap: 2rem; /* Espace entre les cartes */
    flex-wrap: wrap; /* Pour s'adapter aux petits écrans */
}

.player-card {
    width: 200px;
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}

.player-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
}

.player-card img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    margin-bottom: 1rem;
    border-radius: 50%; /* Rond pour les icônes ou photos */
}

.player-card h3 {
    font-size: 1.2rem;
    font-weight: bold;
    margin: 0.5rem 0;
    color: #006400; /* Vert pour attirer l'attention */
}

.player-card p {
    font-size: 0.9rem;
    color: #555;
    margin: 0.5rem 0;
}


.charts {
    max-width: 300px; /* Ajuste cette valeur pour réduire la taille */
    margin: 0 auto; /* Centre le graphique horizontalement */
    margin-bottom: 50px; /* Ajoute un espace sous le graphique */
}

canvas {
    display: block;
    max-width: 300%; /* Limite la largeur à celle du conteneur parent */
    height: auto; /* Maintient le ratio */
}
.general-stats {
    text-align: center;
    margin: 2rem 0;
}

.stats-container {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.stat-card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 1rem;
    width: 250px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
}

.stat-image {
    width: 100%;
    height: auto;
    border-radius: 10px;
    margin-bottom: 1rem;
}

.stat-card h3 {
    font-size: 1.25rem;
    color: #333;
    margin-bottom: 0.5rem;
}

.stat-card p {
    font-size: 1rem;
    color: #666;
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
            <li><a href="Players.php">Gestion des joueurs</a></li>
            <li><a href="matches.php">Gestion des matchs</a></li>
            <li><a href="Statistiques.php">Statistiques</a></li>
        </ul>
        <div class="nav-buttons">
            
            <a href="logout.php" class="logout">Déconnexion</a>
        </div>
    </nav>

    <!-- Vidéo avec texte superposé -->
    <div class="video-container">
        <video autoplay loop muted style="object-fit: cover; height: 400px; width: 100%;">
            <source src="assests/videos/foot2.mp4" type="video/mp4">
            Votre navigateur ne supporte pas la lecture de vidéos.
        </video>
        <div class="overlay-text">
            Bienvenue, Coach!
        </div>
    </div>
<!-- Statistiques générales -->
<div class="general-stats">
    <div class="stats-container">
        <!-- Carte Joueurs Actifs -->
        <div class="stat-card">
            <img src="assests/img/players.jpeg" alt="Joueurs Actifs" class="stat-image">
            <h3>Joueurs Actifs</h3>
            <p>23 joueurs actifs / 33 au total</p>
        </div>
        <!-- Carte Prochain Match -->
        <div class="stat-card">
            <img src="assests/img/soccer-match.jpg" alt="Prochain Match" class="stat-image">
            <h3>Prochain Match</h3>
            <p>Contre US Lusitanos Saint-Maur, le 01/03/2025 à 14h00 (Domicile)</p>
        </div>
        <!-- Carte Performance Globale -->
        <div class="stat-card">
            <img src="assests/img/troph.jpg" alt="Performance Globale" class="stat-image">
            <h3>Performance Globale</h3>
            <p>20.83% Victoires | 12.5% Défaites | 20.83% Nuls</p>
        </div>
    </div>
</div>



<div class="quick-actions">
    <h2>Actions Rapides</h2>
    <div class="actions-container">
        <div class="action-card">
            <img src="assests/img/soccer-player.png" alt="Ajouter un Joueur">
            <a href="Players.php">Ajouter un Joueur</a>
        </div>
        <div class="action-card">
            <img src="assests/img/soccer-field.png" alt="Créer un Match">
            <a href="ajouter_match.php">Créer un Match</a>
        </div>
        <div class="action-card">
            <img src="assests/img/planning (1).png" alt="Préparer un Match">
            <a href="matches.php">Préparer un Match</a>
        </div>
       
        <div class="action-card">
            <img src="assests/img/statistical.png" alt="Voir les Statistiques">
            <a href="Statistiques.php">Voir les Statistiques</a>
        </div>
    </div>
</div>



<!-- Focus Joueurs -->
<div class="player-focus">
    <h2>Focus Joueurs</h2>
    <div class="focus-container">
        <!-- Section pour les joueurs blessés -->
        <?php if (!empty($joueurs_blesse)): ?>
            <div class="player-card">
                <img src="assests/img/injury.png" alt="Blessé">
                <h3>Joueurs Blessés</h3>
                <ul>
                    <?php foreach ($joueurs_blesse as $joueur): ?>
                        <li><?= htmlspecialchars($joueur['Prenom'] . ' ' . $joueur['Nom']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Section pour les joueurs suspendus -->
        <?php if (!empty($joueurs_suspendu)): ?>
            <div class="player-card">
                <img src="assests/img/deactivate.png" alt="Suspendu">
                <h3>Joueurs Suspendus</h3>
                <ul>
                    <?php foreach ($joueurs_suspendu as $joueur): ?>
                        <li><?= htmlspecialchars($joueur['Prenom'] . ' ' . $joueur['Nom']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Graphiques et Visualisations -->
<div class="charts">
    <h2>Performance Globale</h2>
    <canvas id="teamPerformanceChart" width="50" height="50"></canvas>
</div>



    <!-- Modale de confirmation -->
    <div class="modal" id="logout-modal">
        <div class="modal-content">
            <h3>Êtes-vous sûr de vouloir vous déconnecter ?</h3>
            <div class="modal-buttons">
                <a href="logout.php" class="confirm">Oui</a>
                <a href="#" class="cancel" id="cancel-logout">Annuler</a>
            </div>
        </div>
    </div>
    <script>
        // Sélection des éléments
        const logoutBtn = document.querySelector('.logout');
        const logoutModal = document.getElementById('logout-modal');
        const cancelLogout = document.getElementById('cancel-logout');

        // Ouvrir la modale
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            logoutModal.style.display = 'flex';
        });

        // Fermer la modale
        cancelLogout.addEventListener('click', (e) => {
            e.preventDefault();
            logoutModal.style.display = 'none';
        });

        // Fermer en cliquant à l'extérieur
        window.addEventListener('click', (e) => {
            if (e.target === logoutModal) {
                logoutModal.style.display = 'none';
            }
        });
    </script> 
   
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('teamPerformanceChart').getContext('2d');
    const teamPerformanceChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Victoires', 'Défaites', 'Nuls'],
            datasets: [{
                label: 'Performance',
                data: [
                    <?= $stats['victoires'] ?? 0 ?>, 
                    <?= $stats['defaites'] ?? 0 ?>, 
                    <?= $stats['nuls'] ?? 0 ?>
                ],
                backgroundColor: ['#386641', '#c1121f', '#ffd166'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    enabled: true
                }
            }
        }
    });
</script>

 <!-- Footer -->
 <?php include 'includes/footer.php'; ?> 
</body>
</html>
