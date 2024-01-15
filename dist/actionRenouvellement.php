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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST["IdCarteMareyeur"], $_POST["numQuitance"], $_POST["montantRenouv"], $_POST["dateQuitance"], $_POST["dateDebutRenouv"], $_POST["dateFinRenouv"])) {
            $numQuitance = mysqli_real_escape_string($conn, $_POST["numQuitance"]);
            $montantRenouv = mysqli_real_escape_string($conn, $_POST["montantRenouv"]);
            $dateQuitance = mysqli_real_escape_string($conn, $_POST["dateQuitance"]);
            $dateDebutRenouv = mysqli_real_escape_string($conn, $_POST["dateDebutRenouv"]);
            $dateFinRenouv = mysqli_real_escape_string($conn, $_POST["dateFinRenouv"]);
            $idCarteMareyeur = mysqli_real_escape_string($conn, $_POST["IdCarteMareyeur"]);

            // Use prepared statements to prevent SQL injection
            $queryRenouvellement = "INSERT INTO renouvellement (idCarteMareyeur, NumQuitance, MontantRenouv, DateQuitance, DateDebutRenouv, DateFinRenouv) VALUES (?, ?, ?, ?, ?, ?)";

            // Prepare the statement
            $stmt = $conn->prepare($queryRenouvellement);

            // Bind the parameters
            $stmt->bind_param("ssssss", $idCarteMareyeur, $numQuitance, $montantRenouv, $dateQuitance, $dateDebutRenouv, $dateFinRenouv);

            // Execute the statement
            $resultRenouvellement = $stmt->execute();

            // Check if the query was successful
            if ($resultRenouvellement) {
                $_SESSION['successMessage'] = "Renouvellement réussi.";
            } else {
                $_SESSION['errorMessage'] = "Erreur lors du Renouvellement : " . $conn->error;
            }

            // Close the statement
            $stmt->close();

            // Fermer la connexion à la base de données
            $conn->close();

            // Rediriger vers une autre page après le traitement du formulaire
            header('Location: listeRenouvellement.php');
            exit();
        }
    }
?>
