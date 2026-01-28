<?php
include 'db_connection.php';

// Fonction pour récupérer les prochains matchs
function getUpcomingMatches($pdo) {
    $stmt = $pdo->query("
        SELECT Date_match, Heure, Lieu, Nom_equipe_adverse, StatutPreparer
        FROM matchs
        WHERE Date_match >= CURDATE() 
        ORDER BY Date_match ASC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les joueurs blessés
function getInjuredPlayers($pdo) {
    $stmt = $pdo->prepare("SELECT Nom, Prenom FROM joueur WHERE Statut = 'Blessé'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les joueurs suspendus
function getSuspendedPlayers($pdo) {
    $stmt = $pdo->prepare("SELECT Nom, Prenom FROM joueur WHERE Statut = 'Suspendu'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer le nom de l'utilisateur
function getUserName($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT nom_utilisateur FROM utilisateur WHERE id_utilisateur = :id");
    $stmt->execute(['id' => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les statistiques
function getPerformanceStats($pdo) {
    $stmt = $pdo->query("
        SELECT 
            SUM(Resultat = 'Gagné') AS victoires, 
            SUM(Resultat = 'Perdu') AS defaites, 
            SUM(Resultat = 'Nul') AS nuls 
        FROM matchs
    ");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour ajouter un match
function ajouterMatch($pdo, $date_match, $heure, $lieu, $nom_equipe_adverse) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO matchs (Date_match, Heure, Lieu, Nom_equipe_adverse) 
            VALUES (:date_match, :heure, :lieu, :nom_equipe_adverse)
        ");
        $stmt->execute([
            'date_match' => $date_match,
            'heure' => $heure,
            'lieu' => $lieu,
            'nom_equipe_adverse' => $nom_equipe_adverse,
        ]);
        return true;
    } catch (Exception $e) {
        throw new Exception("Erreur lors de l'ajout du match : " . $e->getMessage());
    }
}

// Fonction pour ajouter un joueur
function ajouterJoueur($pdo, $nom, $prenom, $num_licence, $date_naissance, $taille, $poids, $statut, $commentaire) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO joueur (Nom, Prenom, Num_licence, Date_naissance, Taille, Poids, Statut, Commentaire)
            VALUES (:nom, :prenom, :num_licence, :date_naissance, :taille, :poids, :statut, :commentaire)
        ");
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':num_licence' => $num_licence,
            ':date_naissance' => $date_naissance,
            ':taille' => $taille,
            ':poids' => $poids,
            ':statut' => $statut,
            ':commentaire' => $commentaire
        ]);
        return true;
    } catch (Exception $e) {
        throw new Exception("Erreur lors de l'ajout du joueur : " . $e->getMessage());
    }
}

// Fonction pour récupérer les joueurs ayant participé à un match
function getJoueursParMatch($pdo, $id_match) {
    $stmt = $pdo->prepare("
        SELECT p.Id_Joueur, p.Nom, p.Prenom, part.Evaluation 
        FROM participer part
        JOIN joueur p ON part.Id_Joueur = p.Id_Joueur
        WHERE part.Id_match = :id
    ");
    $stmt->execute(['id' => $id_match]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour mettre à jour les évaluations des joueurs
function updateEvaluations($pdo, $id_match, $evaluations) {
    try {
        foreach ($evaluations as $id_joueur => $evaluation) {
            $stmt = $pdo->prepare("
                UPDATE participer 
                SET Evaluation = :evaluation 
                WHERE Id_Joueur = :id_joueur AND Id_match = :id_match
            ");
            $stmt->execute([
                'evaluation' => $evaluation,
                'id_joueur' => $id_joueur,
                'id_match' => $id_match
            ]);
        }
        return true;
    } catch (Exception $e) {
        throw new Exception("Erreur lors de la mise à jour des évaluations : " . $e->getMessage());
    }
}

// Fonction pour récupérer un utilisateur par nom d'utilisateur
function getUserByUsername($pdo, $username) {
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE nom_utilisateur = :username");
    $stmt->execute(['username' => $username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour mettre à jour le résultat d'un match
function updateMatch($pdo, $id_match, $resultat, $score_equipe, $score_equipe_adv) {
    $stmt = $pdo->prepare("
        UPDATE matchs 
        SET Resultat = :resultat, Score_equipe = :score_equipe, Score_equipe_adv = :score_equipe_adv 
        WHERE Id_match = :id_match
    ");
    $stmt->execute([
        'resultat' => $resultat,
        'score_equipe' => $score_equipe,
        'score_equipe_adv' => $score_equipe_adv,
        'id_match' => $id_match,
    ]);
    return true;
}

// Fonction pour marquer un match comme préparé
function markMatchPrepared($pdo, $id_match) {
    $stmt = $pdo->prepare("UPDATE matchs SET StatutPreparer = 1 WHERE Id_match = ?");
    $stmt->execute([$id_match]);
    return true;
}

// Fonction pour supprimer un match
function deleteMatch($pdo, $id_match) {
    $stmt = $pdo->prepare("DELETE FROM Matchs WHERE Id_match = ?");
    $stmt->execute([$id_match]);
    return true;
}

// Fonction pour obtenir les matchs à venir
function getUpcomingMatches1($pdo) {
    $stmt = $pdo->query("
        SELECT * 
        FROM matchs 
        WHERE CONCAT(Date_match, ' ', Heure) > NOW() 
        ORDER BY Date_match ASC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour obtenir les matchs terminés
function getPastMatches($pdo) {
    $stmt = $pdo->query("
        SELECT * 
        FROM matchs 
        WHERE CONCAT(Date_match, ' ', Heure) <= NOW() 
        ORDER BY Date_match DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer un joueur par ID
function getPlayerById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM joueur WHERE Id_Joueur = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Mettre à jour un joueur
function updatePlayer($pdo, $id, $nom, $prenom, $num_licence, $date_naissance, $taille, $poids, $statut, $commentaire) {
    $stmt = $pdo->prepare("
        UPDATE joueur 
        SET Nom = :nom, Prenom = :prenom, Num_licence = :num_licence, Date_naissance = :date_naissance, 
            Taille = :taille, Poids = :poids, Statut = :statut, Commentaire = :commentaire 
        WHERE Id_Joueur = :id
    ");
    return $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'num_licence' => $num_licence,
        'date_naissance' => $date_naissance,
        'taille' => $taille,
        'poids' => $poids,
        'statut' => $statut,
        'commentaire' => $commentaire,
        'id' => $id,
    ]);
}

// Fonction pour récupérer les informations d'un match
function getMatchDetails($pdo, $id_match) {
    $stmt = $pdo->prepare("SELECT * FROM Matchs WHERE Id_match = :id");
    $stmt->execute(['id' => $id_match]);
    return $stmt->fetch();
}

// Récupérer les joueurs actifs
function getActivePlayers($pdo) {
    $stmt = $pdo->query("SELECT * FROM joueur WHERE Statut = 'Actif'");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Vérifier si un joueur est déjà associé à un match
function isPlayerInMatch($pdo, $id_match, $id_joueur) {
    $stmt = $pdo->prepare("SELECT * FROM feuillematch WHERE Id_match = ? AND Id_Joueur = ?");
    $stmt->execute([$id_match, $id_joueur]);
    return $stmt->fetch();
}

// Ajouter un joueur à la feuille de match
function addPlayerToMatch($pdo, $id_match, $id_joueur, $role) {
    $stmt = $pdo->prepare("INSERT INTO feuillematch (Id_match, Id_Joueur, Role) VALUES (?, ?, ?)");
    $stmt->execute([$id_match, $id_joueur, $role]);
}

// Mettre à jour le statut d'un match
function updateMatchStatus($pdo, $id_match) {
    $stmt = $pdo->prepare("UPDATE matchs SET StatutPreparer = 1 WHERE Id_match = ?");
    $stmt->execute([$id_match]);
}

// Fonction pour insérer un nouvel utilisateur
function insertUser($pdo, $username, $email, $password) {
    $stmt = $pdo->prepare("INSERT INTO Utilisateur (nom_utilisateur, email, mot_de_passe) VALUES (:username, :email, :password)");
    return $stmt->execute([
        'username' => $username,
        'email' => $email,
        'password' => $password,
    ]);
}

// Fonction pour mettre à jour les scores d'un match
function updateMatchScore($pdo, $id_match, $score_equipe, $score_adv) {
    $stmt = $pdo->prepare("UPDATE Matchs SET Score_equipe = :score_equipe, Score_equipe_adv = :score_adv WHERE Id_match = :id");
    return $stmt->execute([
        'score_equipe' => $score_equipe,
        'score_adv' => $score_adv,
        'id' => $id_match
    ]);
}

// Fonction pour récupérer les statistiques globales
function getGlobalStats($pdo) {
    $stmt = $pdo->query("
        SELECT 
            COUNT(*) AS total,
            SUM(CASE WHEN Resultat = 'Gagné' THEN 1 ELSE 0 END) AS victoires,
            SUM(CASE WHEN Resultat = 'Perdu' THEN 1 ELSE 0 END) AS defaites,
            SUM(CASE WHEN Resultat = 'Nul' THEN 1 ELSE 0 END) AS nuls
        FROM matchs
    ");
    return $stmt->fetch();
}

// Fonction pour récupérer les statistiques par joueur
function getPlayerStats($pdo) {
    $stmt = $pdo->query("
        SELECT 
            j.Id_Joueur,
            j.Nom,
            j.Prenom,
            j.Statut,
            j.Poste_pref,
            COUNT(CASE WHEN p.Role = 'Titulaire' THEN 1 ELSE NULL END) AS titularisations,
            COUNT(CASE WHEN p.Role = 'Remplaçant' THEN 1 ELSE NULL END) AS remplacements,
            AVG(p.Evaluation) AS moyenne_evaluation,
            COUNT(DISTINCT p.Id_Match) AS matchs_joues,
            ROUND(
                100 * SUM(CASE WHEN m.Resultat = 'Gagné' THEN 1 ELSE 0 END) / NULLIF(COUNT(DISTINCT p.Id_Match), 0),
                2
            ) AS pourcentage_victoires
        FROM joueur j
        LEFT JOIN participer p ON j.Id_Joueur = p.Id_Joueur
        LEFT JOIN matchs m ON p.Id_Match = m.Id_Match
        GROUP BY j.Id_Joueur
    ");
    return $stmt->fetchAll();
}

// Supprimer les joueurs précédemment assignés à un match
function deletePlayersFromMatch($pdo, $id_match) {
    $stmt = $pdo->prepare("DELETE FROM FeuilleMatch WHERE Id_match = :id_match");
    $stmt->execute(['id_match' => $id_match]);
}


// Insérer les nouveaux joueurs assignés à un match
function insertPlayerIntoMatch($pdo, $id_match, $id_joueur, $role) {
    $stmt = $pdo->prepare("INSERT INTO FeuilleMatch (Id_match, Id_Joueur, Role) VALUES (:id_match, :id_joueur, :role)");
    $stmt->execute([
        'id_match' => $id_match,
        'id_joueur' => $id_joueur,
        'role' => $role,
    ]);
}

// Récupérer les détails d'un match par ID
function getMatchById($pdo, $id_match) {
    $stmt = $pdo->prepare("SELECT * FROM matchs WHERE Id_match = :id_match");
    $stmt->execute(['id_match' => $id_match]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Supprimer un joueur par ID
function deletePlayer($pdo, $id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM joueur WHERE Id_Joueur = :id");
        $stmt->execute(['id' => $id]);
        return true; // Retourne true si la suppression est réussie
    } catch (Exception $e) {
        // Journalise l'erreur pour le débogage
        error_log("Erreur lors de la suppression du joueur : " . $e->getMessage());
        return false; // Retourne false en cas d'échec
    }
}

// Fonction pour récupérer les joueurs sélectionnés pour un match
function getJoueursSelectionnesByMatch($pdo, $id_match) {
    $stmt = $pdo->prepare("
        SELECT Id_Joueur, Role 
        FROM FeuilleMatch 
        WHERE Id_match = :id_match
    ");
    $stmt->execute(['id_match' => $id_match]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les joueurs actifs
function getJoueursActifs($pdo) {
    $stmt = $pdo->query("
        SELECT 
            joueur.Id_Joueur,
            joueur.Nom,
            joueur.Prenom,
            joueur.Poste_pref,
            joueur.Taille,
            joueur.Poids,
            joueur.Commentaire
        FROM joueur
        WHERE joueur.Statut = 'Actif'
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les détails d'un match par ID
function getMatchDetailsById($pdo, $id_match) {
    $stmt = $pdo->prepare("SELECT * FROM Matchs WHERE Id_match = :id_match");
    $stmt->execute(['id_match' => $id_match]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour mettre à jour le résultat d'un match
function updateMatchResult($pdo, $id_match, $resultat, $score_equipe, $score_equipe_adv) {
    $stmt = $pdo->prepare("
        UPDATE Matchs 
        SET Resultat = :resultat, Score_equipe = :score_equipe, Score_equipe_adv = :score_equipe_adv 
        WHERE Id_match = :id_match
    ");
    $stmt->execute([
        'resultat' => $resultat,
        'score_equipe' => $score_equipe,
        'score_equipe_adv' => $score_equipe_adv,
        'id_match' => $id_match,
    ]);
}

// Fonction pour mettre à jour le statut d'un match
function markMatchAsPrepared($pdo, $id_match) {
    $stmt = $pdo->prepare("UPDATE Matchs SET StatutPreparer = 1 WHERE Id_match = ?");
    $stmt->execute([$id_match]);
}

// Fonction pour supprimer un match
function deleteMatchById($pdo, $id_match) {
    $stmt = $pdo->prepare("DELETE FROM Matchs WHERE Id_match = ?");
    $stmt->execute([$id_match]);
}

// Fonction pour vérifier si un match est à venir
function getUpcomingMatchById($pdo, $id_match) {
    $stmt = $pdo->prepare("
        SELECT * 
        FROM Matchs 
        WHERE Id_match = ? AND Date_match >= CURDATE()
    ");
    $stmt->execute([$id_match]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction pour insérer des joueurs dans la table `participer`
function insertPlayerParticipation($pdo, $id_joueur, $id_match, $poste, $role) {
    $stmt = $pdo->prepare("
        INSERT INTO participer (Id_Joueur, Id_match, Poste, Role) 
        VALUES (:id_joueur, :id_match, :poste, :role)
    ");
    $stmt->execute([
        'id_joueur' => $id_joueur,
        'id_match' => $id_match,
        'poste' => $poste,
        'role' => $role,
    ]);
}

// Fonction pour récupérer les joueurs sélectionnés et leurs rôles pour un match
function getSelectedPlayers($pdo, $id_match): array {
    $stmt = $pdo->prepare("
        SELECT Id_Joueur, Role
        FROM feuillematch
        WHERE Id_match = ?
    ");
    $stmt->execute([$id_match]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les joueurs actifs avec leurs détails
function getActivePlayersWithDetails($pdo) {
    $stmt = $pdo->query("
        SELECT 
            joueur.Id_Joueur,
            joueur.Nom,
            joueur.Prenom,
            joueur.Poste_pref,
            joueur.Taille,
            joueur.Poids,
            joueur.Commentaire
        FROM joueur
        WHERE joueur.Statut = 'Actif'
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
