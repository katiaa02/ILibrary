<?php
session_start();

// Vérifier si l'utilisateur est connecté
if(isset($_SESSION['id_utilisateur'])) {
    // Vérifier si le formulaire a été soumis
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les données du formulaire
        $id_livre = $_POST['id_livre'];
        $id_utilisateur = $_SESSION['id_utilisateur'];
        $note = $_POST['note'];
        $commentaire = $_POST['commentaire'];
        $date_eval = date("Y-m-d"); // Date actuelle

        // Connexion à la base de données
        $servername = "mysql-ilibrary.alwaysdata.net";
        $username = "ilibrary";
        $password = "IlibraryMZK2024!";
        $dbname = "ilibrary_bd";

        try {
            $bdd = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Requête pour insérer une ligne dans la table evaluer
            $requete = $bdd->prepare("INSERT INTO evaluer (id_livre, id_utilisateur, note, commentaire, date_eval) VALUES (?, ?, ?, ?, ?)");
            $requete->execute(array($id_livre, $id_utilisateur, $note, $commentaire, $date_eval));

            // Redirection vers la page livre.php après avoir noté le livre
            header("Location: achat.php?id=$id_livre");
            exit();
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        // Redirection vers la page noter.php si le formulaire n'a pas été soumis
        header("Location: noter.php");
        exit();
    }
} else {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit();
}
?>
