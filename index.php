<?php
session_start(); // Démarrer la session

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

// Requête SQL pour récupérer les livres
$sql = "SELECT * FROM livre limit 6";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="styles.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Crete+Round:ital@1&display=swap" rel="stylesheet">
    <title>ILibrary</title>
</head>
<body>  




<header>
    <div class="wrapper">
        <h1>ILibrary<span class="blue">.</span></h1>
        <?php
      //  session_start(); // Démarrez la session PHP au début de votre script
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
            <h2><br><br><strong>Explorez, Apprenez, Évaluez  Votre Librairie Intelligente en Ligne !</strong></h2>
        </div>
    </section>
    
    <section id="steps">
        <div class="wrapper">
            <ul>
                <li id="step1">
                    <h4>DÉCOUVRIR</h4>
                    <p>Explorez un vaste catalogue de livres, découvrez de nouveaux auteurs et plongez dans 
                        des genres diversifiés pour enrichir votre expérience de lecture.</p>
                </li>
                <li id="step2">
                    <h4>PERSONNALISER</h4>
                    <p>Profitez de recommandations personnalisées basées sur vos préférences de 
                        lecture, offrant une sélection de livres adaptée à vos goûts uniques.</p>
                </li>
                <li id="step3">
                    <h4>SIMPLIFIER</h4>
                    <p>Profitez d'une interface utilisateur conviviale et de fonctionnalités intuitives qui 
                    simplifient la recherche, l'achat et la gestion de votre bibliothèque en ligne, rendant 
                    ainsi votre expérience aussi fluide que possible.</p>
                </li>
                <div class="clear">
                </div>
            </ul>
        </div>
    </section>
    
    <section id="possibilities">
        <div class="wrapper">
            <div class="book-row">
                <?php
                // Vérification si des livres sont disponibles
                if ($result->num_rows > 0) {
                    // Affichage des livres
                    while ($row = $result->fetch_assoc()) {
                        echo "<article class='book' style='background-image: url(images/livre.jpg)'>";
                        echo '<div class="overlay">';
                        echo '<h4>' . $row['titre'] . '</h4>';
                        // Vérification du statut de connexion de l'utilisateur
                        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                            // Si l'utilisateur est connecté, lien vers la page du livre
                            echo '<a href="livre.php?id=' . $row['id'] . '" class="button-2">Plus d\'infos</a>';
                        } else {
                            // Si l'utilisateur n'est pas connecté, lien vers la page de connexion
                            echo '<a href="connexion.php" class="button-2">Plus d\'infos</a>';
                        }
                        echo '</div>';
                        echo '</article>';
                    }
                } else {
                    echo "Aucun livre trouvé.";
                }

                // Fermeture de la connexion à la base de données
                $conn->close();
                ?>
            </div>
        </div>
    </section>
    
    <section id="contact">
        <div class="wrapper">
            <h3>Contactez nous</h3>
            <p>ILibrary, votre complice littéraire. Notre équipe est là pour enrichir votre expérience
                de lecture. Besoin de recommandations, de conseils ou simplement de partager votre amour pour les 
                livres ? Contactez-nous. Nous sommes déterminés à rendre votre exploration littéraire 
                aussi agréable que possible. Chez ILibrary, chaque page est une nouvelle aventure, et nous 
                sommes impatients de la vivre avec vous !
            </p>
            <form>
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" required>
                <label for="text">Commentaire</label>
                <input type="text" id="commentaire" name="nom" required>
                <input type="submit" value="OK" class="button-3">
            </form>
        </div>
    </section>
    
    <footer>
        <div class="wrapper">
            <h1>ILibrary<span class="blue">
                .</span></h1>
            <div class="copyright">
                Copyright tous droits réservés © 2024.
            </div>
        </div>
    </footer>
    
</body>
</html>
