<!-- traitementModification.php -->
<?php
    function connexion_base() {
        $dbHost = "localhost";
        $dbName = "cartemetier";
        $dbUser = "root";
        $dbMdp = "";
        $conn = new mysqli($dbHost, $dbUser, $dbMdp, $dbName);
        return $conn;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = connexion_base();

        $idCarteMareyeur = $_POST['idCarteMareyeur']; //Champ hidden pour l'ID de la carte

        $numCarte = $_POST['numeroCarte'];
        $categorie = $_POST['categorie'];
        $montantRenouv = $_POST['montantRenouv'];
        $numQuitance = $_POST['numQuitance'];
        $dateQuitance = $_POST['dateQuitance'];
        $dateDebutRenouv = $_POST['dateDebutRenouv'];
        $dateFinRenouv = $_POST['dateFinRenouv'];
        $renewalYear = $_POST['annerenouvele'];


        // Mettre à jour les données de la carte
        $updateRenouvQuery = "UPDATE renouvellement SET 
        NumQuitance = '$numQuitance',
        LibelleCategorie = '$categorie',
        DateQuitance = '$dateQuitance',
        MontantRenouv = '$montantRenouv',
        DateDebutRenouv = '$dateDebutRenouv',
        DateFinRenouv = '$dateFinRenouv',
        RenewalYear = '$renewalYear'
        WHERE idCarteMareyeur = $idCarteMareyeur";
        $conn->query($updateRenouvQuery);
        $conn->close();

        header("Location: listeRenouvellement.php?success=true");
        exit();
    } else {
        header("Location: listeRenouvellement.php?error=true");
        exit();
    }
?>
