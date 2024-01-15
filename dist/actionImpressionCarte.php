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

    // Vérifier si l'ID de l'entreprise est présent dans la session
    if (isset($_SESSION['entrepriseId'])) {
        $entrepriseId = $_SESSION['entrepriseId'];
    } else {
        // Si l'ID de l'entreprise n'est pas présent dans la session, afficher un message d'erreur ou faire autre chose selon vos besoins
        echo "Erreur: ID de l'entreprise manquant.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST["numeroCarte"], $_POST["numQuitance"], $_POST["dateQuitance"], $_POST["dateDebutValid"], $_POST["dateFinValid"], $_POST["siege"], $_POST["categorie"], $_POST["montantCategorie"])){
            $conn = connexion_base();
            $numeroCarte = mysqli_real_escape_string($conn, $_POST["numeroCarte"]);
            $libelleCategorie = mysqli_real_escape_string($conn, $_POST["categorie"]);
            $montantCategorie = mysqli_real_escape_string($conn, $_POST["montantCategorie"]);
            $numQuitance = mysqli_real_escape_string($conn, $_POST["numQuitance"]);
            $dateQuitance = mysqli_real_escape_string($conn, $_POST["dateQuitance"]);
            $dateDebutValid = mysqli_real_escape_string($conn, $_POST["dateDebutValid"]);
            $dateFinValid = mysqli_real_escape_string($conn, $_POST["dateFinValid"]);
            $siege = mysqli_real_escape_string($conn, $_POST["siege"]);

            // Insertion dans la table "cartemareyeur"
            $queryCarteMareyeur = "INSERT INTO cartemareyeur (NumeroCarte, idEntreprise, LibelleCategorie, MontantCategorie, NumQuitance, DateQuitance, DateDebutValid, DateFinValid, Siege) VALUES ('$numeroCarte', '$entrepriseId', '$libelleCategorie', $montantCategorie, '$numQuitance', '$dateQuitance', '$dateDebutValid', '$dateFinValid', '$siege')";
    
            // Exécution de la requête
            $resultCarteMareyeur = $conn->query($queryCarteMareyeur);
    
            if ($resultCarteMareyeur) {
                $successMessage = "Enregistrement réussi.";
            } else {
                $errorMessage = "Erreur lors de l'enregistrement dans la table 'cartemareyeur' : " . $conn->error;
            }
    
            // Fermer la connexion à la base de données
            $conn->close();
        }
    }
    
    unset($_SESSION['personneTemp']);
    
    header('Location: listeCarteMareyeur.php');
    exit();
?>
