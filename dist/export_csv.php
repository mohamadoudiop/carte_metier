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

if (isset($_POST['export_csv'])) {
    // Requête SQL pour récupérer les données des cartes et des personnes liées
    // (Assurez-vous que cette requête est la même que celle utilisée pour afficher le tableau)
    $sql = "SELECT cm.NumeroCarte, cm.LibelleCategorie, cm.NumQuitance, cm.DateDebutValid, cm.Siege,
                p.NomPersonne, p.PrenomPersonne, p.CNI, p.Genre, p.PhonePersonne,
                e.NumRegistreCom
            FROM CarteMareyeur cm
            JOIN Entreprise e ON cm.idEntreprise = e.idEntreprise
            JOIN Personne p ON e.idPersonne = p.idPersonne";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Entête du fichier CSV
        $csvContent = "Numéro carte,Catégorie,Numéro quitance,Date début Validité,Siège,Nom,Prénom,CNI,Genre,Téléphone,Registre de commerce\n";
        while ($row = $result->fetch_assoc()) {
            // Ajouter chaque ligne de données dans le fichier CSV
            $csvContent .= '"' . $row['NumeroCarte'] . '","' . $row['LibelleCategorie'] . '","' . $row['NumQuitance'] . '","' . $row['DateDebutValid'] . '","' . $row['Siege'] . '","' . $row['NomPersonne'] . '","' . $row['PrenomPersonne'] . '","' . $row['CNI'] . '","' . $row['Genre'] . '","' . $row['PhonePersonne'] . '","' . $row['NumRegistreCom'] . '"' . "\n";
        }

        // Entête HTTP pour indiquer que c'est un fichier CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="liste_cartes.csv"');

        // Écrire le contenu CSV dans la sortie
        echo $csvContent;
    }

    // Fermer la connexion à la base de données
    mysqli_close($conn);
    exit(); // Terminer l'exécution du script après avoir généré le fichier CSV
}
?>
