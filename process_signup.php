<?php
include 'includes/db_connection.php'; // Connexion à la base de données
include 'includes/database.php'; // Contient les fonctions pour manipuler la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        // Appel de la fonction pour insérer l'utilisateur
        if (insertUser($pdo, $username, $email, $password)) {
            // Affichage de la boîte modale de succès
            echo "
            <html lang='fr'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
                <style>
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    body {
                        font-family: 'Poppins', sans-serif;
                        background-color: #000;
                        color: white;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                    }
                    .modal {
                        background-color: #333;
                        padding: 2rem;
                        border-radius: 10px;
                        text-align: center;
                        max-width: 400px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                    }
                    .modal h2 {
                        margin-bottom: 1rem;
                        color: #1D4D04;
                    }
                    .modal p {
                        margin-bottom: 1.5rem;
                        font-size: 1rem;
                    }
                    .modal button {
                        background-color: #1D4D04;
                        color: white;
                        border: none;
                        padding: 0.8rem 1.2rem;
                        font-size: 1rem;
                        border-radius: 5px;
                        cursor: pointer;
                        transition: background 0.3s;
                    }
                    .modal button:hover {
                        background-color: #145003;
                    }
                </style>
            </head>
            <body>
                <div class='modal'>
                    <h2>Inscription réussie !</h2>
                    <p>Vous pouvez maintenant vous connecter.</p>
                    <button onclick=\"location.href='login.php'\">Se connecter</button>
                </div>
            </body>
            </html>
            ";
            exit();
        } else {
            throw new Exception("Échec de l'insertion dans la base de données.");
        }
    } catch (Exception $e) {
        // Gestion des erreurs
        echo "Erreur : " . htmlspecialchars($e->getMessage());
    }
}
?>
