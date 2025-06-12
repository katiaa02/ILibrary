<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="styles.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Crete+Round:ital@1&display=swap" rel="stylesheet">
    <title>ILibrary - Tendances</title>
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

    <div class="liste">
        <div class="wrapper">
        <h1>Les livres les plus en tendances</h1>
            <div class="book-row">
                <?php
            //Connexion à la base de données
                $servername = "mysql-ilibrary.alwaysdata.net";
                $username = "ilibrary";
                $password = "IlibraryMZK2024!";
                $dbname = "ilibrary_bd";
                
                // Connexion à la base de données avec PDO
                try {
                    $bdd = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
                    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    // Requête pour récupérer les 9 livres les plus tendances
                    $requete = $bdd->query("SELECT titre, id_livre FROM livre ORDER BY date_sortie DESC LIMIT 9");
                  
                    // Affichage des livres
                    while ($donnees = $requete->fetch()) {
                        echo "<article class='book' style='background-image: url(images/livre.jpg)'>";
                        echo "<div class='overlay'>";
                        echo "<h4>" . $donnees['titre'] . "</h4>";
                        echo "<a href='livre.php?id=" . $donnees['id_livre'] . "' class='button-2'>Plus d'infos</a>";
                        echo "</div>";
                        echo "</article>";
                    }
                    
                    // Fermeture de la requête
                    $requete->closeCursor();
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }
                ?>
            </div>
        </div>
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
