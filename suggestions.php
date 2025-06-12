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
                    echo "<li><a href='achat.php'> Achat </a></li>";
                    echo "<li><a > $prenom </a></li>";
        
                    echo "<li><a href='deconnexion.php'> Déconnexion </a></li>"; // Ajoutez un lien de déconnexion si nécessaire
                    echo "</ul>";
                    echo "</nav>";
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }
            } else {
                // Redirigez l'utilisateur vers la page de connexion s'il n'est pas connecté
                header("Location: connexion.php");
                exit(); // Assurez-vous d'arrêter l'exécution du script après la redirection
            }
            ?>
        </div>
    </header>

    <div class="liste">
        <div class="wrapper">
            <h1>Livres Pour Vous</h1>
            <div class="book-row">
                <?php
                // Inclure le fichier contenant les fonctions de recommandation
                require_once "ia.php";

                // Appel de la fonction de recommandation pour récupérer les recommandations de livres
                $nbide = 9; // Nombre de recommandations à afficher
                $recommandations = suggererLivres($id_utilisateur , $bdd);

                // Affichage des livres recommandés
                foreach ($recommandations as $id_livre) {
                    $queryInfoLivre = "SELECT id_livre, titre, prix FROM livre WHERE id_livre = $id_livre";
                    $resultInfoLivre = $bdd->query($queryInfoLivre);
                    $rowInfoLivre = $resultInfoLivre->fetch(PDO::FETCH_ASSOC);

                    // Afficher le livre avec ses informations
                    echo "<article class='book' style='background-image: url(images/livre.jpg)'>";
                    echo "<div class='overlay'>";
                    echo "<h4>Titre: " . $rowInfoLivre['titre'] . "<br></h4>";
                    echo "<h4>Livre ID: $id_livre</h4>";
                    echo "<a href='livre.php?id=$id_livre' class='button-2'>Plus d'infos</a>";
                    echo "</div>";
                    echo "</article>";
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
