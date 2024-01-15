<?php
    // Remplacez les informations de connexion ci-dessous par les vôtres
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

    // Requête pour récupérer les régions
    $sql = "SELECT LibelleRegion FROM region";
    $resultat = $conn->query($sql);

    // Créer un tableau pour stocker les noms de régions
    $regions = array();
    while ($ligne = $resultat->fetch_assoc()) {
        $regions[] = $ligne['LibelleRegion'];
    }

    // Fermer le résultat de la requête
    $resultat->close();

    // Fermer la connexion à la base de données
    $conn->close();

    // Renvoyer les données au format JSON
    echo json_encode($regions);
?>
