i<?php

// Vérification de la session
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body>
    <nav>
        <div class="logo">
            <img src="assests/img/logo.png" alt="Logo">
        </div>
        <ul>
            <li><a href="Dashboard.php">Accueil</a></li>
            <li><a href="Players.php" class="<?= basename($_SERVER['PHP_SELF']) === 'Players.php' ? 'active' : '' ?>">Gestion des joueurs</a></li>
            <li><a href="matches.php" class="<?= basename($_SERVER['PHP_SELF']) === 'matches.php' ? 'active' : '' ?>">Gestion des matchs</a></li>
            <li><a href="Statistiques.php" class="<?= basename($_SERVER['PHP_SELF']) === 'Statistiques.php' ? 'active' : '' ?>">Statistiques</a></li>
        </ul>
        <div class="nav-buttons">
            <a href="logout.php" class="logout">Déconnexion</a>
        </div>
    </nav>
