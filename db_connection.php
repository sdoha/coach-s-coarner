<?php
// Paramètres de connexion à la base de données
$host = 'mysql-lfarh.alwaysdata.net';
$dbname = 'lfarh_gestion_equipe_sport'; // Remplacez par le nom exact de votre base
$username = 'lfarh'; // Nom d'utilisateur MySQL Alwaysdata
$password = 'Essadkinaima123'; // Mot de passe MySQL Alwaysdata

try {
    // Création de l'objet PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configuration des options PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En cas d'erreur, afficher un message
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
