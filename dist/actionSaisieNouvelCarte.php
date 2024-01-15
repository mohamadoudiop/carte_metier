<?php
    session_start();

    function connexion_base(){
        $dbHost = "localhost";
        $dbName = "cartemetier";
        $dbUser = "root";
        $dbMdp = "";
        $conn = new mysqli($dbHost, $dbUser, $dbMdp, $dbName);
        return $conn;
    }

    // Connexion à la base de données
    $conn = connexion_base();

    // Vérifier si la connexion a réussi
    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        // Requête SQL pour récupérer le prénom et le nom de l'utilisateur
        $requete = "SELECT PrenomUtilisateur, NomUtilisateur FROM Utilisateur WHERE EmailUtilisateur = '$email'";
        $resultat = mysqli_query($conn, $requete);

        // Vérifier si la requête a retourné un résultat
        if (mysqli_num_rows($resultat) == 1) {
            $utilisateur = mysqli_fetch_assoc($resultat);
            $prenomUtilisateur = $utilisateur['PrenomUtilisateur'];
            $nomUtilisateur = $utilisateur['NomUtilisateur'];
        }
    } else {
        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        header("Location: index.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST["prenom"], $_POST["nom"], $_POST["datenaissance"], $_POST["lieunaissance"], $_POST["cni"], $_POST["datedelivrance"], $_POST["adresse"], $_POST["genre"], $_POST["telephone"], $_POST["profession"], $_POST["photo"], $_POST["numRegistreCom"], $_POST["adresseEntreprise"], $_POST["region"], $_POST["departement"], $_POST["activiteEntreprise"], $_POST["telephoneEntreprise"], $_POST["denomination"])){
            $prenom = mysqli_real_escape_string($conn, $_POST["prenom"]);
            $nom = mysqli_real_escape_string($conn, $_POST["nom"]);
            $datenaissance = mysqli_real_escape_string($conn, $_POST["datenaissance"]);
            $lieunaissance = mysqli_real_escape_string($conn, $_POST["lieunaissance"]);
            $cni = mysqli_real_escape_string($conn, $_POST["cni"]);
            $datedelivrance = mysqli_real_escape_string($conn, $_POST["datedelivrance"]);
            $adresse = mysqli_real_escape_string($conn, $_POST["adresse"]);                     
            $genre = mysqli_real_escape_string($conn, $_POST["genre"]);                         
            $telephone = mysqli_real_escape_string($conn, $_POST["telephone"]);                 
            $profession = mysqli_real_escape_string($conn, $_POST["profession"]);
            $photo = mysqli_real_escape_string($conn, $_POST["photo"]);
            $numRegistreCom = mysqli_real_escape_string($conn, $_POST["numRegistreCom"]);
            $adresseEntreprise = mysqli_real_escape_string($conn, $_POST["adresseEntreprise"]);
            $regionEntreprise = mysqli_real_escape_string($conn, $_POST["region"]);
            $departementEntreprise = mysqli_real_escape_string($conn, $_POST["departement"]);
            $activiteEntreprise = mysqli_real_escape_string($conn, $_POST["activiteEntreprise"]);
            $phoneEntreprise = mysqli_real_escape_string($conn, $_POST["telephoneEntreprise"]);
            $denomination = mysqli_real_escape_string($conn, $_POST["denomination"]);

            // Insertion dans la table "personne"
            $queryPersonne = "INSERT INTO personne (PrenomPersonne, NomPersonne, DateNaissance, LieuNaissance, CNI, DateDelivranceCNI, AdressePersonne, Genre, PhonePersonne, Profession, Photo) VALUES 
            ('$prenom', '$nom', '$datenaissance', '$lieunaissance', '$cni', '$datedelivrance', '$adresse', '$genre', '$telephone', '$profession', '$photo')";

            $resultPersonne = $conn->query($queryPersonne);

            // Récupération de l'identifiant de la personne insérée
            $personneId = $conn->insert_id;

            // Insertion dans la table "entreprise"
            $queryEntreprise = "INSERT INTO entreprise (Denomination, RegionEntreprise, DepartementEntreprise, AdresseEntreprise, Activite, PhoneEntreprise, NumRegistreCom, idPersonne) VALUES 
            ('$denomination', '$regionEntreprise', '$departementEntreprise', '$adresseEntreprise', '$activiteEntreprise', '$phoneEntreprise', '$numRegistreCom', '$personneId')";

            // Exécution de la requête et récupération de l'identifiant de l'entreprise insérée
            $resultEntreprise = $conn->query($queryEntreprise);
            $entrepriseId = $conn->insert_id;

            if ($resultPersonne && $resultEntreprise) {
                // Stocker l'ID de l'entreprise dans une variable de session
                $_SESSION['entrepriseId'] = $entrepriseId;
                $successMessage = "Enregistrement réussi.";
            } else {
                $errorMessage = "Erreur lors de l'enregistrement : " . $conn->error;
            }

            // Fermer la connexion à la base de données
            $conn->close();

            // Rediriger vers la page d'accueil ou une autre page si nécessaire
            header('Location: impressionCarte.php');
            exit();
        }
    }
?>
