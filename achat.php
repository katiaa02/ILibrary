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
            // Redirigez l'utilisateur vers la page de connexion s'il n'est pas connecté
            echo "Veuillez vous connecter pour voir vos livres.";// Assurez-vous d'arrêter l'exécution du script après la redirection
        }
        ?>
    </div>
</header>
    
<div class="liste">
    <div class="container4">
        <h1>Livres que vous avez achetés</h1>
        <?php
        // Connexion à la base de données
        $servername = "mysql-ilibrary.alwaysdata.net";
        $username = "ilibrary";
        $password = "IlibraryMZK2024!";
        $dbname = "ilibrary_bd";

       // $id_utilisateur = $_SESSION['id_utilisateur'];

        // Connexion à la base de données avec PDO
        try {
            $bdd = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Requête pour récupérer les livres achetés par l'utilisateur avec leurs notes
            $requete = $bdd->prepare("SELECT livre.*, acheter.date_achat, evaluer.note FROM acheter INNER JOIN livre ON acheter.id_livre = livre.id_livre LEFT JOIN evaluer ON acheter.id_livre = evaluer.id_livre AND evaluer.id_utilisateur = ? WHERE acheter.id_utilisateur = ?");
            $requete->execute([$id_utilisateur, $id_utilisateur]);

            while ($donnees = $requete->fetch()) {
                echo '<div class="book-details">';
                echo '<div class="book-image">';
                echo '<img src="images/livre.jpg" alt="Image du livre">';
                echo '</div>';
                echo '<div class="book-info">';
                echo '<h4>' . $donnees['titre'] . '</h4>';
                echo '<p>ISBN : ' . $donnees['ISBN'] . '</p>';
                echo '<p>Langue : ' . $donnees['langue'] . '</p>';
                echo '<p>Age Recommandé : ' . $donnees['age_recommande'] . '</p>';
                echo '<p>Volume : ' . $donnees['volume'] . '</p>';
                echo '<p>Prix : ' . $donnees['prix'] . '</p>';
                echo '<p>Date de Sortie : ' . $donnees['date_sortie'] . '</p>';
                echo '<p>Date d\'Achat : ' . $donnees['date_achat'] . '</p>';

                // Vérifier si l'utilisateur a déjà noté ce livre
                if ($donnees['note'] !== null) {
                    echo "<p>Votre note : " . $donnees['note'] . "</p>";
                    echo "<a href='noter.php?id_livre=" . $donnees['id_livre'] . "' class='button-3' style='margin-top: 20px;'>Modifier la note</a>";
                } else {
                    echo "<a href='noter.php?id_livre=" . $donnees['id_livre'] . "' class='button-3' style='margin-top: 20px;'>Noter</a>";
                }

                echo '</div>';
                echo '</div>';
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        ?>
    </div>
    <div class="clear"></div>
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
