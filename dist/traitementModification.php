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

    $numeroCarte = $_POST['numeroCarte'];
    $libelleCategorie = $_POST['libelleCategorie'];
    $montantCategorie = $_POST['montantCategorie'];    
    $numQuitance = $_POST['numQuitance'];
    $dateDebutValid = $_POST['dateDebutValid'];
    $dateFinValid = $_POST['dateFinValid'];
    $siege = $_POST['siege'];
    $denomination = $_POST['denomination'];
    $adresseEntreprise = $_POST['adresseEntreprise'];
    $regionEntreprise = $_POST['regionEntreprise'];
    $departementEntreprise = $_POST['departementEntreprise'];
    $phoneEntreprise = $_POST['phoneEntreprise'];
    $numRegistreCom = $_POST['numRegistreCom'];
    $activite = $_POST['activite'];
    $nomPersonne = $_POST['nomPersonne'];
    $prenomPersonne = $_POST['prenomPersonne'];
    $dateNaissance = $_POST['dateNaissance'];
    $lieuNaissance = $_POST['lieuNaissance'];
    $cni = $_POST['cni'];
    $dateDelivranceCNI = $_POST['dateDelivranceCNI'];
    $genre = $_POST['genre'];
    $phonePersonne = $_POST['phonePersonne'];
    $adressePersonne = $_POST['adressePersonne'];
    $profession = $_POST['profession'];


    // Mettre à jour les données de la carte
    $updateCarteQuery = "UPDATE cartemareyeur SET 
    NumeroCarte = '$numeroCarte', 
    LibelleCategorie = '$libelleCategorie',
    MontantCategorie = '$montantCategorie',
    NumQuitance = '$numQuitance',
    DateDebutValid = '$dateDebutValid',
    DateFinValid = '$dateFinValid',
    Siege = '$siege'
    WHERE idCarteMareyeur = $idCarteMareyeur";
    $conn->query($updateCarteQuery);

    // Mettre à jour les données de l'entreprise
    $updateEntrepriseQuery = "UPDATE entreprise SET 
    Denomination = '$denomination',
    AdresseEntreprise = '$adresseEntreprise',
    RegionEntreprise = '$regionEntreprise',
    DepartementEntreprise = '$departementEntreprise',
    PhoneEntreprise = '$phoneEntreprise',
    NumRegistreCom = '$numRegistreCom',
    Activite = '$activite'
    WHERE idEntreprise = (SELECT idEntreprise FROM cartemareyeur WHERE idCarteMareyeur = $idCarteMareyeur)";
    $conn->query($updateEntrepriseQuery);

    // Mettre à jour les données de la personne
    $updatePersonneQuery = "UPDATE personne SET 
    NomPersonne = '$nomPersonne',
    PrenomPersonne = '$prenomPersonne',
    DateNaissance = '$dateNaissance',
    LieuNaissance = '$lieuNaissance',
    CNI = '$cni',
    DateDelivranceCNI = '$dateDelivranceCNI',
    Genre = '$genre',
    PhonePersonne = '$phonePersonne',
    AdressePersonne = '$adressePersonne',
    Profession = '$profession'
    WHERE idPersonne = (SELECT idPersonne FROM entreprise WHERE idEntreprise = (SELECT idEntreprise FROM cartemareyeur WHERE idCarteMareyeur = $idCarteMareyeur))";
    $conn->query($updatePersonneQuery);


    $conn->close();

    header("Location: modifierCarte.php?success=true");
    exit();
} else {
    header("Location: modifierCarte.php?error=true");
    exit();
}
?>
