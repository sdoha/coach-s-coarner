<footer>
    <div class="footer-container">
        <!-- Logo et légende au centre -->
        <div class="footer-logo">
            <img src="assests/img/blackback.png" alt="Logo">
            <p>"Construire l'avenir, un match à la fois."</p>
        </div>

        <!-- Infos alignées horizontalement -->
        <div class="footer-info">
            <div class="footer-section">
                <h4>Navigation</h4>
                <ul>
                    <li><a href="dashboard.php">Accueil</a></li>
                    <li><a href="players.php">Gestion des joueurs</a></li>
                    <li><a href="matches.php">Gestion des matchs</a></li>
                    <li><a href="statistiques.php">Statistiques</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Contact</h4>
                <p><i class="bi bi-envelope"></i> <span class="highlight">Email :</span>&nbsp;<a href="mailto:contact@coachscorner.com">contact@coachscorner.com</a></p>
                <p><i class="bi bi-telephone"></i> <span class="highlight">Téléphone :</span>&nbsp;+33 6 12 34 56 78</p>
                <p><i class="bi bi-geo-alt"></i> <span class="highlight">Adresse :</span>&nbsp;123 Rue des Stades, Paris, France</p>
            </div>

            <div class="footer-section social-section">
                <h4>Suivez-Nous</h4>
                <div class="social-icons">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>© 2024 Coach's Corner. Tous droits réservés.</p>
    </div>

    <!-- Ajouter Bootstrap Icons pour les icônes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        footer {
            background-color: #000; /* Fond noir */
            color: white; /* Texte blanc */
            padding: 2rem 0;
            font-family: 'Poppins', sans-serif;
        }
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        .footer-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .footer-logo img {
            height: 80px;
            margin-bottom: 1rem;
        }
        .footer-logo p {
            font-size: 1rem;
            color: #aaa;
        }
        .footer-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 3rem; /* Augmenter l'espace entre les sections */
            flex-wrap: wrap;
        }
        .footer-section {
            flex: 1;
            text-align: left;
        }
        .footer-section h4 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid #1D4D04;
            display: inline-block;
        }
        .footer-section ul {
            list-style: none;
            padding: 0;
        }
        .footer-section ul li {
            margin: 0.5rem 0;
        }
        .footer-section ul li a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }
        .footer-section ul li a:hover {
            color: rgb(59, 148, 11); /* Vert au survol */
        }
        .footer-section p {
            margin: 0.5rem 0;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }
        .footer-section p i {
            margin-right: 0.5rem;
        }
        .footer-section p a {
            color: white; /* Texte blanc pour l'email */
            text-decoration: none; /* Supprime le soulignement */
            transition: color 0.3s;
        }
        .footer-section p a:hover {
            color: rgb(61, 148, 14); /* Vert au survol */
        }
        .social-section {
            flex: 1;
            text-align: right; /* Aligner à droite */
        }
        .social-icons {
            margin-top: 1rem;
            display: flex;
            gap: 1.5rem; /* Espacement entre les icônes */
            justify-content: flex-end; /* Aligne à droite */
        }
        .social-icons a {
            text-decoration: none;
            color: white;
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #333;
            border-radius: 50%;
            transition: background-color 0.3s, color 0.3s;
        }
        .social-icons a:hover {
            background-color: #1D4D04; /* Vert au survol */
            color: white;
        }
        .footer-bottom {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9rem;
            border-top: 1px solid #333;
            padding-top: 1rem;
        }
        /* Style pour les mots mis en avant */
        .highlight {
            color: rgb(62, 153, 80); /* Couleur verte */
            font-weight: bold; /* Gras */
        }
    </style>
</footer>
