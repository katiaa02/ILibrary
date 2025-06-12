<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noter un livre - ILibrary</title>
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
            <h3>Noter un livre</h3>
            <?php
            // Insérez ici le code PHP pour traiter la soumission du formulaire de notation
            ?>
            <form method="post" action="traitement_noter.php">
                <div class="input-group">
                    <label for="note">Note :</label>
                    <input type="number" id="note" name="note" min="1" max="5" required>
                </div>
                <div class="input-group">
                    <label for="commentaire">Commentaire :</label>
                    <textarea id="commentaire" name="commentaire" rows="5" cols="50"></textarea>
                </div>
                <input type="hidden" name="id_livre" value="<?php echo $_GET['id']; ?>">
                <button type="submit" name="submit">Noter</button>
            </form>
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
