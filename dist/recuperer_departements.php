<?php
if (isset($_POST['region'])) {
    $selectedRegion = $_POST['region'];

    $dbHost = "localhost";
    $dbName = "cartemetier";
    $dbUser = "root";
    $dbMdp = ""; 

    // Connexion à la base de données
    $conn = new mysqli($dbHost, $dbUser, $dbMdp, $dbName); 

    // Vérifier si la connexion a réussi
    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }

    // Requête pour récupérer les départements associés à la région sélectionnée
    $sql = "SELECT LibelleDepartement FROM departement WHERE idRegion = (
            SELECT idRegion FROM region WHERE LibelleRegion = '$selectedRegion'
        )";
    $resultat = $conn->query($sql);

    // Créer un tableau pour stocker les noms de départements
    $departements = array();
    while ($ligne = $resultat->fetch_assoc()) {
        $departements[] = $ligne['LibelleDepartement'];
    }

    // Fermer le résultat de la requête
    $resultat->close();

    // Fermer la connexion à la base de données
    $conn->close();

    // Renvoyer les données au format JSON
    echo json_encode($departements);
} else {
    // Si la région n'est pas présente dans la requête POST, renvoyer un tableau vide au format JSON
    echo json_encode(array());
}
?>
