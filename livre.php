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


<div class="livre">
    <div class="container4">
        <?php
        // Connexion à la base de données
        $servername = "mysql-ilibrary.alwaysdata.net";
        $username = "ilibrary";
        $password = "IlibraryMZK2024!";
        $dbname = "ilibrary_bd";

        // Vérifier si l'identifiant du livre est passé dans l'URL
        if (isset($_GET['id'])) {
            // Récupérer l'identifiant du livre depuis l'URL
            $id_livre = $_GET['id'];

            try {
                // Connexion à la base de données avec PDO
                $bdd = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
                $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                // Requête pour récupérer les informations du livre avec l'identifiant spécifié
                $requete = $bdd->prepare("SELECT livre.*, auteur.nom_a, auteur.prenom_a, categorie.nom_cat FROM livre INNER JOIN auteur ON livre.id_auteur = auteur.id_auteur INNER JOIN categorie ON livre.id_cat = categorie.id_cat WHERE livre.id_livre = ?");
                $requete->execute(array($id_livre));

                // Affichage des informations du livre
                if ($donnees = $requete->fetch()) {
                    echo "<h1>Informations Du Livre</h1>";
                    echo "<div class='book-details'>";
                    echo "<div class='book-image'>";
                    echo "<h2>" . $donnees['titre'] . "</h2>";
                    echo "<img src='images/livre.jpg' alt='Image du livre'>";
                    echo "</div>";
                    echo "<div class='book-info'>";
                    echo "<p><strong>ISBN :</strong> " . $donnees['ISBN'] . "</p>";
                    echo "<p><strong>Langue :</strong> " . $donnees['langue'] . "</p>";
                    echo "<p><strong>Age Recommander :</strong> " . $donnees['age_recommande'] . "</p>";
                    echo "<p><strong>Volume :</strong> " . $donnees['volume'] . " pages</p>";
                    echo "<p><strong>Prix :</strong> " . $donnees['prix'] . " €</p>";
                    echo "<p><strong>Date de Sortie :</strong> " . $donnees['date_sortie'] . "</p>";
                    echo "<p><strong>Hauteur :</strong> " . $donnees['nom_a'] . " " . $donnees['prenom_a'] . "</p>";
                    echo "<p><strong>Catégorie :</strong> " . $donnees['nom_cat'] . "</p>";
                    echo "</div>";
                    echo "</div>";

                    // Vérifier si l'utilisateur est connecté
                  //  session_start();
                    if(isset($_SESSION['id_utilisateur'])) {
                        // Vérifier si l'utilisateur a déjà acheté ce livre
                        $id_utilisateur = $_SESSION['id_utilisateur'];
                        $verif_requete = $bdd->prepare("SELECT * FROM acheter WHERE id_livre = ? AND id_utilisateur = ?");
                        $verif_requete->execute(array($id_livre, $id_utilisateur));
                        $verif_result = $verif_requete->fetch();

                        if($verif_result) {
                            // Bouton pour noter le livre
                            echo "<a href='noter.php?id=".$id_livre."' class='button-3' style='margin-top: 20px;'>Noter</a>";
                        } else {
                            // Formulaire pour acheter le livre
                            echo "<form action='' method='post'>";
                            echo "<input type='hidden' name='id_livre' value='" . $id_livre . "'>";
                            echo "<input type='submit' name='submit' value='Acheter' class='button-3' style='margin-top: 20px;'>";
                            echo "</form>";

                            // Si le formulaire est soumis, insérer dans la table "acheter"
                            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
                                // Requête pour insérer dans la table "acheter"
                                $insert_requete = $bdd->prepare("INSERT INTO acheter (id_utilisateur, id_livre, date_achat) VALUES (?, ?, NOW())");
                                if ($insert_requete->execute(array($id_utilisateur, $id_livre))) {
                                    // Insertion réussie, redirection vers la page achat.php
                                    header("Location: achat.php");
                                    exit;
                                } else {
                                    // Insertion échouée, afficher un message d'erreur
                                    echo "Erreur lors de l'insertion dans la base de données.";
                                }
                            }
                        }
                    } else {
                        echo "<p>Veuillez vous connecter pour acheter ce livre.</p>";
                    }
                } else {
                    echo "Livre non trouvé.";
                }
                
                // Fermeture de la requête
                $requete->closeCursor();
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
        } else {
            echo "Identifiant du livre non spécifié.";
        }
        ?>
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
