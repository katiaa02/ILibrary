<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Smart Mail</title>
    <link rel="stylesheet" href="styles.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Crete+Round:ital@1&display=swap" rel="stylesheet">
    <style>
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <header>
        <div class="wrapper">
            <h1>ILibrary<span class="blue">.</span></h1>
            <nav>
                <ul>
                    <li><a href="index.php"> Accueil </a></li>
                    <li><a href="recherche.php"> Recherche </a></li>
                    <li><a href="tendances.php"> Tendances </a></li>
                    <li><a href="suggestions.php"> Suggestions </a></li>
                    <li><a href="connexion.php"> Se connecter </a></li>
                    <li><a href="inscription.php"> S'inscrire </a></li>
                </ul>
            </nav>
        </div>
    </header>
    <section id="main-image">
    <div class="container">
        <div class="login-box">
            <h3>Connexion</h3>
            <?php
            session_start();

            // Informations de connexion à la base de données
            $servername = "mysql-ilibrary.alwaysdata.net";
            $username = "ilibrary";
            $password = "IlibraryMZK2024!";
            $dbname = "ilibrary_bd";

            // Vérification si le formulaire a été soumis
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Création de la connexion à la base de données
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Vérification de la connexion
                if ($conn->connect_error) {
                    die("La connexion à la base de données a échoué : " . $conn->connect_error);
                }

                // Récupération des données du formulaire
                $email = $_POST['email'];
                $mot_de_passe = $_POST['mot_de_passe'];

                // Requête SQL pour vérifier les informations d'identification
                $sql = "SELECT * FROM utilisateur WHERE email='$email'";
                $result = $conn->query($sql);

                // Vérification des résultats de la requête
                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if (password_verify($mot_de_passe, $row['mot_de_passe'])) {
                        // L'utilisateur est connecté avec succès, rediriger vers la page tendances.php
                        $_SESSION['logged_in'] = true;
                        $_SESSION['id_utilisateur'] = $row['id_utilisateur']; // Sauvegarder l'ID de l'utilisateur dans la session
                        header("Location: tendances.php");
                        exit(); // Arrêt du script
                    } else {
                        // Mot de passe incorrect, afficher un message d'erreur
                        echo "<p class='error-message'>Mot de passe incorrect. Veuillez réessayer.</p>";
                    }
                } else {
                    // L'utilisateur n'existe pas, afficher un message d'erreur
                    echo "<p class='error-message'>Cet utilisateur n'existe pas.</p>";
                }

                $conn->close(); // Fermeture de la connexion à la base de données
            }
            ?>
            <form method="post" action="connexion.php">
                <div class="input-group">
                    <label for="email">Adresse e-mail</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                </div>
                <button type="submit" name="submit">Se connecter</button>
            </form>
            <p>Pas encore inscrit ? <a href="inscription.php">S'inscrire</a></p>
        </div>
    </div>
    </section>
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
