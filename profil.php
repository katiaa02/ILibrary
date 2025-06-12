<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - ILibrary</title>
    <link rel="stylesheet" href="styles.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Crete+Round:ital@1&display=swap" rel="stylesheet">
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
        <div class="container2">
            <div class="signin-box"> 
                <h3>Profil</h3>
                <?php
                // Connexion à la base de données
                $servername = "mysql-ilibrary.alwaysdata.net";
                $username = "ilibrary";
                $password = "IlibraryMZK2024!";
                $dbname = "ilibrary_bd";
                
                $bdd = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
                
                // Vérifier si l'utilisateur est connecté
               // session_start();
                if (!isset($_SESSION['id_utilisateur'])) {
                    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
                    header("Location: connexion.php");
                    exit(); // Arrêter l'exécution du script
                }
                
                // Récupérer l'ID de l'utilisateur connecté
                $id_utilisateur = $_SESSION['id_utilisateur'];
                
                // Fonction pour récupérer les informations de l'utilisateur depuis la base de données
                function getUtilisateurInfo($bdd, $id_utilisateur) {
                    $requete = $bdd->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
                    $requete->execute([$id_utilisateur]);
                    return $requete->fetch(PDO::FETCH_ASSOC);
                }
                
                // Récupérer les informations de l'utilisateur
                $utilisateur = getUtilisateurInfo($bdd, $id_utilisateur);
                
                // Définir une variable pour stocker le message
                $message = "";
                
                // Vérifier si le formulaire a été soumis
                if ($_SERVER["REQUEST_METHOD"] === "POST") {
                    // Récupérer les nouvelles valeurs depuis le formulaire
                    $nom = $_POST['nom'];
                    $prenom = $_POST['prenom'];
                    $date_naissance = $_POST['date_naissance'];
                    $sexe = $_POST['sexe'];
                    $profession = $_POST['profession'];
                    $geographie = $_POST['geographie'];
                    $email = $_POST['email'];
                    
                    // Vérifier si les valeurs soumises sont différentes des valeurs actuelles
                    if ($nom !== $utilisateur['nom_u'] || $prenom !== $utilisateur['prenom_u'] || $date_naissance !== $utilisateur['date_de_naiss'] ||
                        $sexe !== $utilisateur['sexe'] || $profession !== $utilisateur['profession'] || $geographie !== $utilisateur['géographie'] ||
                        $email !== $utilisateur['email']) {
                        
                        // Mettre à jour les informations de l'utilisateur dans la base de données
                        $requete_update = $bdd->prepare("UPDATE utilisateur SET nom_u = ?, prenom_u = ?, date_de_naiss = ?, sexe = ?, profession = ?, géographie = ?, email = ? WHERE id_utilisateur = ?");
                        $requete_update->execute([$nom, $prenom, $date_naissance, $sexe, $profession, $geographie, $email, $id_utilisateur]);

                        // Mettre à jour les informations de l'utilisateur après la mise à jour
                        $utilisateur = getUtilisateurInfo($bdd, $id_utilisateur);
                        
                        // Définir le message de succès
                        $message = "Les modifications ont été enregistrées.";
                    } else {
                        // Définir le message d'aucune modification
                        $message = "Aucune modification n'a été effectuée.";
                    }
                }
                ?>
                <?php if (!empty($message)) : ?>
                    <p style='color: green;'><?php echo $message; ?></p>
                <?php endif; ?>
                <form action="profil.php" method="post">
                    <div class="half-width">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" value="<?php echo $utilisateur['nom_u']; ?>" required>
</div>
<div class="half-width">
<label for="prenom">Prénom</label>
<input type="text" id="prenom" name="prenom" value="<?php echo $utilisateur['prenom_u']; ?>" required>
</div>
<div class="half-width">
<label for="date_naissance">Date de naissance :</label>
<input type="date" name="date_naissance" id="date_naissance" value="<?php echo $utilisateur['date_de_naiss']; ?>" required>
</div>
<div class="half-width">  
<label for="sexe">Sexe</label>
<select id="sexe" name="sexe" required>
<option value="Homme" <?php if ($utilisateur['sexe'] === "Homme") echo "selected"; ?>>Homme</option>
<option value="Femme" <?php if ($utilisateur['sexe'] === "Femme") echo "selected"; ?>>Femme</option>
</select> 
</div>
<div class="half-width">  
<label for="profession">Profession</label>
<select id="profession" name="profession" required>
<option value="Agriculteur" <?php if ($utilisateur['profession'] === "Agriculteur") echo "selected"; ?>>Agriculteur</option>
<option value="Artisan" <?php if ($utilisateur['profession'] === "Artisan") echo "selected"; ?>>Artisan</option>
<option value="Employé" <?php if ($utilisateur['profession'] === "Employé") echo "selected"; ?>>Employé</option>
<option value="Etudiant" <?php if ($utilisateur['profession'] === "Etudiant") echo "selected"; ?>>Etudiant</option>
</select> 
</div>
<div class="half-width">  
<label for="geographie">Géographie</label>
<select id="geographie" name="geographie" required>
<option value="Paris" <?php if ($utilisateur['géographie'] === "Paris") echo "selected"; ?>>Paris</option>
<option value="Marseille" <?php if ($utilisateur['géographie'] === "Marseille") echo "selected"; ?>>Marseille</option>
<option value="Lyon" <?php if ($utilisateur['géographie'] === "Lyon") echo "selected"; ?>>Lyon</option>
<option value="Toulouse" <?php if ($utilisateur['géographie'] === "Toulouse") echo "selected"; ?>>Toulouse</option>
</select> 
</div>
<div class="half-width">
<label for="email">Adresse e-mail</label>
<input type="email" id="email" name="email" value="<?php echo $utilisateur['email']; ?>" required>
</div>
<button type="submit" class="button-3">Enregistrer</button>
</form>
</div>
</div>
</section>
<footer>
<div class="wrapper">
<h1>ILibrary<span class="blue">.</span></h1>
<div class="copyright">Copyright tous droits réservés © 2024.</div>
</div>
</footer>
</body>
</html>
