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

if (isset($_POST['export_entreprise'])) {
    // Requête SQL pour récupérer les données des cartes et des personnes liées
    // (Assurez-vous que cette requête est la même que celle utilisée pour afficher le tableau)
    $sql = "SELECT * FROM entreprise";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Entête du fichier CSV 
        $csvContent = "Dénomination, Adresse, Région, Département, Activités, Téléphone, Registre de commerce\n";
        while ($row = $result->fetch_assoc()) {
            // Ajouter chaque ligne de données dans le fichier CSV
            $csvContent .= '"' . $row['Denomination'] . '","' . $row['AdresseEntreprise'] . '","' . $row['RegionEntreprise'] . '","' . $row['DepartementEntreprise'] . '","' . $row['Activite'] . '","' . $row['PhoneEntreprise'] . '","' . $row['NumRegistreCom'] . '"' . "\n";
        }

        // Entête HTTP pour indiquer que c'est un fichier CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="liste_entreprises.csv"');

        // Écrire le contenu CSV dans la sortie
        echo $csvContent;
    }

    // Fermer la connexion à la base de données
    mysqli_close($conn);
    exit(); // Terminer l'exécution du script après avoir généré le fichier CSV
}
?>
