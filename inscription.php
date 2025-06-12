<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Smart Mail</title>
    <link rel="stylesheet" href="styles.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Crete+Round:ital@1&display=swap" rel="stylesheet">
    <script>
        document.addEventListener("DOMContentLoaded", function () {
          const form = document.querySelector("form");

          form.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(form);

            const xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function () {
              if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                  const response = xhr.responseText;
                  const messageContainer = document.getElementById("message-container");

                  if (response === "email_exist") {
                    messageContainer.innerHTML = "L'email existe déjà dans la base de données.";
                  } else if (response === "success") {
                    window.location.href = "connexion.php";
                  } else {
                    console.error("Erreur lors de l'inscription.");
                  }
                } else {
                  console.error("Erreur lors de la requête XMLHttpRequest");
                }
              }
            };

            xhr.open("POST", "verification_email.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send(new URLSearchParams(formData).toString());
          });
        });
    </script>
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
    <section id="main-image">
        <div class="container2">
            <div class="signin-box"> 
                <h3>Inscription</h3>
                <div id="message-container" style="color: red;"></div>
                <form action="inscription.php" method="post">
                    <div class="half-width">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" required>
                    </div>
                    <div class="half-width">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom" required>
                    </div>
    
                    <div class="half-width">
    
                        <label for="date_naissance">Date de naissance :</label>
                        <input type="date" name="date_naissance" id="date_naissance" required>
                    </div>
                    <div class="half-width">  
                            <label for="sexe">Sexe</label>
                            
                            <select id="sexe" name="sexe"  required>
                                <option value=""disabled selected>Sélectionnez le sexe</option>
                                <option value="Homme">Homme</option>
                                <option value="Femme">Femme</option>
                            </select> 
                    </div>
                    
                    <div class="half-width">  
                        <label for="profession">Profession</label>
                        
                        <select id="profession" name="profession"  required>
                            <option value=""disabled selected>Sélectionnez la profession</option>
                            <option value="Agriculteur">Agriculteur</option>
                            <option value="Artisan">Artisan</option>
                            <option value="Employé">Employé</option>
                            <option value="Etudiant">Etudiant</option>
                        </select> 
                </div>
                <div class="half-width">  
                    <label for="geographie">Géographie</label>
                    
                    <select id="geographie" name="geographie"  required>
                        <option value=""disabled selected>Sélectionnez la géographie</option>
                        <option value="paris">Paris</option>
                        <option value="marseille">Marseille</option>
                        <option value="lyon">Lyon</option>
                        <option value="toulouse">Toulouse</option>
                    </select> 
            </div>
                    
            <div class="half-width">
                        
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" required>
            
            </div>
            <div class="half-width">
                
                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>  
                   
                    <button type="submit" class="button-3">S'inscrire</button>
                </form>
                <p>Déjà inscrit ? <a href="connexion.php">Se connecter</a></p>
            </div>
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
