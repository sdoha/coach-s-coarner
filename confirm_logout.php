<?php
session_start();
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
    <title>Confirmation Déconnexion</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }
        .confirmation-box {
            text-align: center;
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .confirmation-box h2 {
            margin-bottom: 20px;
        }
        button {
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
            transition: background-color 0.3s ease;
        }
        .btn-yes {
            background-color: red;
            color: white;
        }
        .btn-yes:hover {
            background-color: darkred;
        }
        .btn-no {
            background-color: #f4f4f4;
            color: black;
            border: 1px solid #ccc;
        }
        .btn-no:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="confirmation-box">
        <h2>Êtes-vous sûr de vouloir vous déconnecter ?</h2>
        <form action="logout.php" method="POST" style="display:inline;">
            <button type="submit" class="btn-yes">Oui</button>
        </form>
        <form action="dashboard.php" method="GET" style="display:inline;">
            <button type="submit" class="btn-no">Annuler</button>
        </form>
    </div>
</body>
</html>
