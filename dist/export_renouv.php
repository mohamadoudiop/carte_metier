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

if (isset($_POST['export_renouv'])) {
    // Requête SQL pour récupérer les données des cartes et des personnes liées
    // (Assurez-vous que cette requête est la même que celle utilisée pour afficher le tableau)
    $sql = "SELECT cm.idCarteMareyeur, cm.NumeroCarte, cm.LibelleCategorie, cm.NumQuitance, cm.DateDebutValid, cm.Siege,
                r.NumQuitance, r.DateQuitance, DateDebutRenouv, DateFinRenouv, RenewalYear
            FROM CarteMareyeur cm
            JOIN Renouvellement r ON cm.idCarteMareyeur = r.idCarteMareyeur";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Entête du fichier CSV
        $csvContent = "Numéro carte, Catégorie, Numéro quitance, Date quitance, Date début Renouvellement, Date Fin Renouvellement, Année renouvelée\n";
        while ($row = $result->fetch_assoc()) {
            // Ajouter chaque ligne de données dans le fichier CSV
            $csvContent .= '"' . $row['NumeroCarte'] . '","' . $row['LibelleCategorie'] . '","' . $row['NumQuitance'] . '","' . $row['DateQuitance'] . '","' . $row['DateDebutRenouv'] . '","' . $row['DateFinRenouv'] . '","' . $row['RenewalYear'] . '"' . "\n";
        }

        // Entête HTTP pour indiquer que c'est un fichier CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="liste_renouvellement.csv"');

        // Écrire le contenu CSV dans la sortie
        echo $csvContent;
    }

    // Fermer la connexion à la base de données
    mysqli_close($conn);
    exit(); // Terminer l'exécution du script après avoir généré le fichier CSV
}
?>
