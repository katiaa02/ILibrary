<?php
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

// Récupération des données soumises via le formulaire
$email = $_POST['email'];
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$date_naissance = $_POST['date_naissance'];
$sexe = $_POST['sexe'];
$profession = $_POST['profession'];
$geographie = $_POST['geographie'];
$mot_de_passe = $_POST['mot_de_passe'];

// Hachage du mot de passe
$mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);

// Requête SQL pour vérifier si l'email existe déjà dans la table utilisateur
$sql = "SELECT * FROM utilisateur WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // L'email existe déjà dans la base de données
    echo "email_exist";
} else {
    // L'email n'existe pas encore dans la base de données
    // Insérer les données de l'utilisateur dans la table utilisateur
    $sql_insert = "INSERT INTO utilisateur (nom_u, prenom_u, date_de_naiss, email, sexe, mot_de_passe, profession, géographie) VALUES ('$nom', '$prenom', '$date_naissance', '$email', '$sexe', '$mot_de_passe_hache', '$profession', '$geographie')";

    if ($conn->query($sql_insert) === TRUE) {
        echo "success";
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>
