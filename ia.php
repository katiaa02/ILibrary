<?php

$servername = "mysql-ilibrary.alwaysdata.net";
$username = "ilibrary";
$password = "IlibraryMZK2024!";
$dbname = "ilibrary_bd";

// Connexion à la base de données avec PDO
try {
    
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   // echo "Connexion réussie !";
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Fonction pour calculer la similarité entre deux vecteurs de caractéristiques utilisateur
function calculateUserSimilarity($user1, $user2) {
    // Variables pour calculer la somme des carrés des différences
    $sum_of_squares = 0;

    // Calcul de la somme des carrés des différences entre les caractéristiques
    foreach ($user1 as $key => $value) {
        // Convertir les valeurs en nombres
        $value1 = is_numeric($value) ? $value : 0;
        $value2 = is_numeric($user2[$key]) ? $user2[$key] : 0;

        // Calculer la différence et ajouter au carré
        $difference = $value1 - $value2;
        $sum_of_squares += $difference * $difference;
    }

    // Calcul de la similarité en utilisant la distance euclidienne normalisée
    $similarity = 1 / (1 + sqrt($sum_of_squares));

    return $similarity;
}


// Fonction pour récupérer les caractéristiques statiques d'un utilisateur à partir de son ID

function getUserStaticProfile($userId, $pdo) {
    $stmt = $pdo->prepare("SELECT sexe, profession, géographie FROM utilisateur WHERE id_utilisateur = ?");
    $stmt->execute([$userId]);
    $userProfile = $stmt->fetch(PDO::FETCH_ASSOC);
    return $userProfile;
}

// Fonction pour trouver des utilisateurs similaires à un utilisateur donné
function findSimilarUsers($userId, $pdo) {
    // Récupérer le profil statique de l'utilisateur donné
    $userProfile = getUserStaticProfile($userId, $pdo);

    // Récupérer tous les utilisateurs (à l'exclusion de l'utilisateur donné)
    $stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateur WHERE id_utilisateur != ?");
    $stmt->execute([$userId]);
    $allUsers = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Calculer la similarité entre l'utilisateur donné et tous les autres utilisateurs
    $similarities = [];
    foreach ($allUsers as $otherUserId) {
        $otherUserProfile = getUserStaticProfile($otherUserId, $pdo);
        $similarity = calculateUserSimilarity($userProfile, $otherUserProfile);
        $similarities[$otherUserId] = $similarity;
    }

    // Trier les similarités par ordre décroissant
    arsort($similarities);

    return $similarities;
}

// Fonction pour calculer la pondération des évaluations en fonction de la date
function weightFunction($evaluationDate, $decayRate = 0.1, $timeScale = 1) {
    // Cette fonction attribue un poids à une évaluation en fonction de sa date.
    // Plus l'évaluation est récente, plus le poids est élevé.
    // Les paramètres $decayRate et $timeScale permettent de régler la décroissance exponentielle de la pondération.

    // Calcul du nombre de jours écoulés depuis l'évaluation
    $daysSinceEvaluation = (strtotime("now") - strtotime($evaluationDate)) / (60 * 60 * 24);

    // Calcul de la pondération en utilisant une décroissance exponentielle
    $weight = exp(-$decayRate * $daysSinceEvaluation / $timeScale);

    // Retourne la pondération calculée
    return $weight;
}

// Fonction pour suggérer des livres à un utilisateur spécifique
function suggererLivres($id_utilisateur, $pdo) {
    // Vérifier si l'utilisateur a des évaluations ou s'il a donné des évaluations négatives
    $stmtUserRatings = $pdo->prepare("SELECT COUNT(*) FROM evaluer WHERE id_utilisateur = ? AND (note >= 3 OR note IS NULL)");
    $stmtUserRatings->execute([$id_utilisateur]);
    $userRatingCount = $stmtUserRatings->fetchColumn();

    if ($userRatingCount == 0) {
        $stmtLatestBooks = $pdo->prepare("SELECT id_livre FROM livre ORDER BY date_sortie DESC LIMIT 5");
        $stmtLatestBooks->execute();
        $latestBooks = $stmtLatestBooks->fetchAll(PDO::FETCH_COLUMN);

        // Sélectionner les 9 livres les mieux notés
        $stmtTopRatedBooks = $pdo->prepare("SELECT id_livre FROM evaluer WHERE note >= 4 GROUP BY id_livre ORDER BY AVG(note) DESC LIMIT 4");
        $stmtTopRatedBooks->execute();
        $topRatedBooks = $stmtTopRatedBooks->fetchAll(PDO::FETCH_COLUMN);

        // Fusionner les deux ensembles de livres
        $recommendedBooks = array_merge($latestBooks, $topRatedBooks);

        // Retourner les identifiants des livres recommandés
        return array_unique($recommendedBooks);
    } else {
    // Trouver des utilisateurs similaires à l'utilisateur donné
    $similarUsers = findSimilarUsers($id_utilisateur, $pdo);

    // Récupérer les évaluations de l'utilisateur cible
    $stmtUserRatings = $pdo->prepare("SELECT id_livre, note, date_eval FROM evaluer WHERE id_utilisateur = ?");
    $stmtUserRatings->execute([$id_utilisateur]);
    $userRatings = $stmtUserRatings->fetchAll(PDO::FETCH_ASSOC);

    // Construire le vecteur d'évaluations de l'utilisateur cible
    $userVector = [];
    foreach ($userRatings as $rating) {
        // Appliquer une pondération aux évaluations en fonction de leur pertinence (par exemple, évaluations récentes ont un poids plus élevé)
        $weight = weightFunction($rating['date_eval']); // Définir votre propre fonction de pondération en fonction de la date d'évaluation
        $userVector[$rating['id_livre']] = $rating['note'] * $weight;
    }

    // Sélectionner les livres recommandés (on sélectionne les livres les mieux notés par les utilisateurs similaires)
    $recommendedBooks = [];
    foreach ($similarUsers as $similarUserId => $similarity) {
        $stmtUserRatings = $pdo->prepare("SELECT id_livre FROM evaluer WHERE id_utilisateur = ? AND note >= 4");
        $stmtUserRatings->execute([$similarUserId]);
        $userHighRatings = $stmtUserRatings->fetchAll(PDO::FETCH_COLUMN);
        
        foreach ($userHighRatings as $bookId) {
            // Vérifier si l'utilisateur cible n'a pas déjà évalué ce livre
            if (!isset($userVector[$bookId])) {
                $recommendedBooks[$bookId] = true;
            }
        }
    }

   // Filtrer les livres déjà évalués par l'utilisateur
   foreach ($userVector as $bookId => $rating) {
    unset($recommendedBooks[$bookId]);
}
}
// Filtrer les livres par catégorie
$userCategories = [];
foreach ($userRatings as $rating) {
    $stmtBookCategory = $pdo->prepare("SELECT id_cat FROM livre WHERE id_livre = ?");
    $stmtBookCategory->execute([$rating['id_livre']]);
    $categorie = $stmtBookCategory->fetchColumn();
    $userCategories[$rating['id_livre']] = $categorie;

}

$filteredBooks = [];
foreach ($recommendedBooks as $bookId => $value) {
    $stmtBookCategory = $pdo->prepare("SELECT id_cat FROM livre WHERE id_livre = ?");
    $stmtBookCategory->execute([$bookId]);
    $categorie = $stmtBookCategory->fetchColumn();
    
    // Vérifier si le livre a été évalué par l'utilisateur
   
    
        // Comparer les catégories du livre recommandé avec celles des livres évalués par l'utilisateur
        if (in_array($categorie, $userCategories)) {
            // Si la catégorie du livre recommandé est dans la liste des catégories évaluées par l'utilisateur, ajouter le livre à la liste des livres filtrés
            $filteredBooks[$bookId] = true;
        }
    
}


// Retourner les identifiants des livres filtrés
return array_keys($filteredBooks);

}



?>
