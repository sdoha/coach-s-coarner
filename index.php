<?php
include 'includes/db_connection.php';
// Connexion à la base de données


if (isset($_GET['registered']) && $_GET['registered'] === 'true') {
    echo "<p style='color: green; text-align: center;'>Inscription réussie ! Vous pouvez vous connecter.</p>";
}

// Gestion du formulaire
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

   // Requête avec le nom correct de la table (en minuscule)
$stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE nom_utilisateur = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();


    if ($user && password_verify($password, $user['mot_de_passe'])) {
        // Démarrer la session et rediriger vers le dashboard
        session_start();
        $_SESSION['user_id'] = $user['id_utilisateur'];
        header('Location: Dashboard.php');
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>Connexion</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            height: 100vh;
        }
        .split-screen {
            display: flex;
            width: 100%;
        }
        .split-screen .left {
            flex: 1;
            background: url('assests/img/ballonlogin.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .split-screen .left h1 {
            font-size: 3.5rem;
            font-weight: bold;
            animation: slideDown 1s ease-out;
        }
        .split-screen .left p {
            font-size: 1.5rem;
            margin-top: 1rem;
            animation: fadeIn 1.5s ease-out;
        }
        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        .split-screen .right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            background-color: #fff;
        }
        .split-screen .right form {
            background: #000;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            animation: popup 0.8s ease-out;
        }
        @keyframes popup {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
        .split-screen .right h2 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            color: #fff;
            text-align: center;
        }
        .input-container {
            margin-bottom: 1.5rem;
        }
        .input-container label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #fff;
        }
        .input-container input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            background-color: #333;
            color: #fff;
        }
        .input-container input::placeholder {
            color: #bbb;
        }
        .checkbox-container {
            display: flex;
            align-items: center;
            color: #fff;
        }
        .checkbox-container input {
            margin-right: 0.5rem;
        }
        .signup-btn {
            width: 100%;
            padding: 0.8rem;
            border: none;
            border-radius: 5px;
            background: #1D4D04;
            color: #fff;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        .signup-btn:hover {
            background: #145003;
        }
        .legal {
            margin-top: 1rem;
            font-size: 0.8rem;
            color: #ccc;
            text-align: center;
        }
        .legal a {
            color: #fff;
            text-decoration: none;
        }
        .legal a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 1rem;
        }
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: #000;
            padding: 2rem;
            border-radius: 10px;
            max-width: 400px;
            width: 90%;
            color: #fff;
            text-align: center;
        }
        .modal-content h2 {
            margin-bottom: 1.5rem;
        }
        .modal-content input {
            margin-bottom: 1rem;
            padding: 0.8rem;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;
            background: #333;
            color: #fff;
        }
        .modal-content button {
            margin-top: 1rem;
            padding: 0.8rem;
            width: 100%;
            background: #1D4D04;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
        }
        .modal-content button:hover {
            background: #145003;
        }
        .modal-close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5rem;
            color: #fff;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="split-screen">
        <!-- Section gauche -->
        <div class="left">
            <div>
                <h1>Bienvenue dans</h1>
                <p>Coach's Corner</p>
            </div>
        </div>

        <!-- Section droite -->
        <div class="right">
            <form method="POST" action="">
                <h2>Connexion</h2>
                <?php if ($error): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <div class="input-container">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" placeholder="Votre nom d'utilisateur" required>
                </div>
                <div class="input-container">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
                </div>
                <div class="checkbox-container">
                    <input type="checkbox" id="remember-me">
                    <label for="remember-me">Se souvenir de moi</label>
                </div>
                <button type="submit" class="signup-btn">Se connecter</button>
                <div class="legal">
                    <p>
                        <a href="forgot_password.php">Mot de passe oublié ?</a>
                    </p>
                    <p>
                        Vous n'avez pas de compte ? <a href="#" id="signup-link">S'inscrire</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal pour l'inscription -->
    <div class="modal" id="signup-modal">
        <div class="modal-content">
            <span class="modal-close" id="close-modal">&times;</span>
            <h2>Inscription</h2>
            <form method="POST" action="process_signup.php">
                <input type="text" name="username" placeholder="Nom d'utilisateur" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <button type="submit">S'inscrire</button>
            </form>
        </div>
    </div>

    <script>
        const signupLink = document.getElementById('signup-link');
        const signupModal = document.getElementById('signup-modal');
        const closeModal = document.getElementById('close-modal');

        signupLink.addEventListener('click', (e) => {
            e.preventDefault();
            signupModal.style.display = 'flex';
        });

        closeModal.addEventListener('click', () => {
            signupModal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === signupModal) {
                signupModal.style.display = 'none';
            }
        });
    </script>
</body>
</html>
