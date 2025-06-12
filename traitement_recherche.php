<?php
// Connexion à la base de données
$servername = "mysql-ilibrary.alwaysdata.net";
$username = "ilibrary";
$password = "IlibraryMZK2024!";
$dbname = "ilibrary_bd";

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Récupération du critère de recherche
$critere = $_POST['critere'];

// Requête SQL pour rechercher dans la table livre
$sql = "SELECT livre.*, auteur.nom_a, auteur.prenom_a, categorie.nom_cat 
        FROM livre 
        INNER JOIN auteur ON livre.id_auteur = auteur.id_auteur 
        INNER JOIN categorie ON livre.id_cat = categorie.id_cat 
        WHERE livre.titre LIKE '%$critere%' 
        OR livre.ISBN LIKE '%$critere%' 
        OR livre.langue LIKE '%$critere%' 
        OR livre.age_recommande LIKE '%$critere%' 
        OR livre.volume LIKE '%$critere%' 
        OR livre.prix LIKE '%$critere%' 
        OR livre.date_sortie LIKE '%$critere%' 
        OR auteur.nom_a LIKE '%$critere%' 
        OR auteur.prenom_a LIKE '%$critere%' 
        OR auteur.genre_litteraire LIKE '%$critere%' 
        OR auteur.nationalite LIKE '%$critere%' 
        OR auteur.biographie LIKE '%$critere%' 
        OR categorie.nom_cat LIKE '%$critere%'";

// Exécution de la requête
$result = $conn->query($sql);

// Vérification des résultats
if ($result->num_rows > 0) {
    // Stockage des résultats dans un tableau associatif
    $resultats_recherche = [];
    while ($row = $result->fetch_assoc()) {
        $resultats_recherche[] = $row;
    }
} else {
    // Aucun résultat trouvé
    $resultats_recherche = [];
}

// Fermeture de la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link href="styles.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Crete+Round:ital@1&display=swap" rel="stylesheet">
    <title>ILibrary - Recherche</title>
</head>
<body>

<header>
    <div class="wrapper">
        <h1>ILibrary<span class="blue">.</span> 
        </h1>
        <nav>
            <ul>
                <li><a href="index.php"> Accueil </a></li>
                <li><a href="recherche.php"> Recherche </a></li>
                <li><a href="tendances.php"> Tendances </a></li>
                <li><a href="suggestions.php"> Suggestions </a></li>
                <li><a href="connexion.php"> se connecter </a></li>
                <li><a href="inscription.php"> s'inscrire </a></li>
            </ul>
        </nav>
    </div>
</header>

<section id="main-box">
    <div class="wrapper">
        <div class="search-box">
            <form action="traitement_recherche.php" method="post" class="search-form">
                <label for="critere">Recherche</label>
                <input type="text" name="critere" id="critere" placeholder="Saisissez le titre, le genre, l'année de sortie ou la hauteur">
                <button type="submit">Rechercher</button>
            </form>
        </div>
    </div>
</section>

<div class="wrapper">
    <!-- Résultats de recherche ici -->
    <?php
    // Vérification si des résultats de recherche sont disponibles
    if (!empty($resultats_recherche)) {
        echo "<div class='book-row'>";
        // Afficher chaque livre dans une boucle
        foreach ($resultats_recherche as $livre) {
            echo "<article class='book' style='background-image: url(images/livre.jpg)'>";
                        echo "<div class='overlay'>";
                        echo "<h4>" . $livre['titre'] . "</h4>";
                        echo "<p><strong>Auteur :</strong> " . $livre['nom_a'] . " " . $livre['prenom_a'] . "</p>";
                        echo "<p><strong>Genre :</strong> " . $livre['nom_cat'] . "</p>";
                        echo "<p><strong>Année de sortie :</strong> " . $livre['date_sortie'] . "</p>";
                        echo "<p><strong>Prix :</strong> " . $livre['prix'] . " €</p>";
                        echo "<a href='livre.php?id=" . $livre['id_livre'] . "' class='button-2'>Plus d'infos</a>";
                        echo "</div>";
                        echo "</article>";
            
        }
        echo "</div>";
    } else {
        // Aucun résultat trouvé
        echo "<p>Aucun résultat trouvé pour votre recherche.</p>";
    }
    ?>
</div>

<footer>
    <div class="wrapper">
        <h1>ILibrary<span class="blue">.</span></h1>
        <div class="copyright">
            Copyright tous droits réservés © 2024.
        </div>
    </div>
</footer>

</body>
</html>
