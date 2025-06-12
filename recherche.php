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
        <h1>ILibrary<span class="blue">.</span></h1>
        <?php
        session_start(); // Démarrez la session PHP au début de votre script
        $id_utilisateur = isset($_SESSION['id_utilisateur']) ? $_SESSION['id_utilisateur'] : null;

        if ($id_utilisateur) { // Après avoir récupéré l'ID de l'utilisateur depuis la session
            // Connexion à la base de données
            $servername = "mysql-ilibrary.alwaysdata.net";
            $username = "ilibrary";
            $password = "IlibraryMZK2024!";
            $dbname = "ilibrary_bd";

            // Connexion à la base de données avec PDO
            try {
                $bdd = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
                $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Ajoutez le code pour récupérer le prénom de l'utilisateur depuis la base de données
                $stmt = $bdd->prepare("SELECT prenom_u FROM utilisateur WHERE id_utilisateur = ?");
                $stmt->execute([$id_utilisateur]);
                $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
                $prenom = $userInfo['prenom_u'];

                // Ensuite, affichez le prénom dans le header
                echo "<nav>";
                echo "<ul>";
                echo "<li><a href='index.php'> Accueil </a></li>";
                echo "<li><a href='recherche.php'> Recherche </a></li>";
                echo "<li><a href='tendances.php'> Tendances </a></li>";
                echo "<li><a href='suggestions.php'> Suggestions </a></li>";
                echo "<li><a href='achat.php'> Achats </a></li>";
                echo "<li><a href='profil.php'> $prenom </a></li>";
    
                echo "<li><a href='deconnexion.php'> Déconnexion </a></li>"; // Ajoutez un lien de déconnexion si nécessaire
                echo "</ul>";
                echo "</nav>";
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
        } else {
            echo '<div class="wrapper">
           
                <nav>
                    <ul>
                        <li><a href="index.php"> Accueil </a></li>
                        <li><a href="recherche.php"> Recherche </a></li>
                        <li><a href="tendances.php"> Tendances </a></li>
                        <li><a href="suggestions.php"> Suggestions </a></li>
                        <li><a href="connexion.php"> se connecter </a></li>
                        <li><a href="inscription.php"> s\'inscrire </a></li>
                    </ul>
                </nav>
            </div>';
        }
        ?>
    </div>
</header>

<section id="main-image">
    <div class="wrapper">
        <div class="search-container">
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
    if (isset($resultats_recherche) && !empty($resultats_recherche)) {
        echo "<div class='book-row'>";
        // Afficher chaque livre dans une boucle
        foreach ($resultats_recherche as $livre) {
            echo "<div class='book'>";
            echo "<img src='images/livre.jpg' alt='Image du livre'>";
            echo "<h4>" . $livre['titre'] . "</h4>";
            echo "<p><strong>Auteur :</strong> " . $livre['nom_a'] . " " . $livre['prenom_a'] . "</p>";
            echo "<p><strong>Genre :</strong> " . $livre['nom_cat'] . "</p>";
            echo "<p><strong>Année de sortie :</strong> " . $livre['date_sortie'] . "</p>";
            echo "<p><strong>Prix :</strong> " . $livre['prix'] . " €</p>";
            echo "</div>";
        }
        echo "</div>";
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
