<?php
include 'includes/db_connection.php'; // Assurez-vous que le chemin est correct

// Vérifiez si la connexion à la base de données est valide
if (!$pdo) {
    die("Erreur : La connexion à la base de données n'a pas été établie.");
}

// Nom d'utilisateur et mot de passe à hacher
$username = 'admin'; // Assurez-vous que cet utilisateur existe dans votre table
$plainPassword = 'admin123'; // Mot de passe en clair à hacher

// Hachage du mot de passe
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// Mise à jour du mot de passe dans la base de données
$stmt = $pdo->prepare("UPDATE Utilisateur SET mot_de_passe = :hashedPassword WHERE nom_utilisateur = :username");
$stmt->execute([
    'hashedPassword' => $hashedPassword,
    'username' => $username,
]);

echo "Mot de passe mis à jour avec succès.";
?>
