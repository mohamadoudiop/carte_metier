<?php
    function connexion_base(){
        $dbHost = "localhost";
        $dbName = "cartemetier";
        $dbUser = "root";
        $dbMdp = "";
        $conn = new mysqli($dbHost, $dbUser, $dbMdp, $dbName);
        return $conn;
    }

    if (isset($_GET['idCarteMareyeur'])) {
        $idCarteMareyeur = $_GET['idCarteMareyeur'];

        // Vérifier si le formulaire de confirmation a été soumis
        if (isset($_POST['confirm'])) {
            $conn = connexion_base();
    
            // Démarrer une transaction
            $conn->begin_transaction();

            // Suppression de CarteMareyeur
            $queryCarteMareyeur = "DELETE FROM cartemareyeur
                                    WHERE idCarteMareyeur = ?";

            // Préparation et exécution de la requête CarteMareyeur
            $stmtCarteMareyeur = $conn->prepare($queryCarteMareyeur);
            $stmtCarteMareyeur->bind_param("i", $idCarteMareyeur);
            $successCarteMareyeur = $stmtCarteMareyeur->execute();

            // Suppression d'Entreprise liée à CarteMareyeur
            $queryEntreprise = "DELETE FROM entreprise
                                WHERE idEntreprise NOT IN (
                                    SELECT idEntreprise
                                    FROM cartemareyeur
                                )";
            
            // Préparation et exécution de la requête Entreprise
            $stmtEntreprise = $conn->prepare($queryEntreprise);
            $successEntreprise = $stmtEntreprise->execute();

            // Suppression de Personne liée à Entreprise liée à CarteMareyeur
            $queryPersonne = "DELETE FROM personne
                            WHERE idPersonne NOT IN (
                                SELECT idPersonne
                                FROM entreprise
                            )";
            
            // Préparation et exécution de la requête Personne
            $stmtPersonne = $conn->prepare($queryPersonne);
            $successPersonne = $stmtPersonne->execute();

            // Vérification de la réussite des requêtes
            $success = $successCarteMareyeur && $successEntreprise && $successPersonne;


            if ($success) {
                // Valider la transaction
                $conn->commit();
                echo "Suppression réussie.";
            } else {
                // Annuler la transaction en cas d'échec
                $conn->rollback();
                echo "Erreur lors de la suppression : " . $conn->error;
            }

            // Fermer les requêtes préparées
            $stmtPersonne->close();
            $stmtEntreprise->close();
            $stmtCarteMareyeur->close();

        // Fermer la connexion à la base de données
        $conn->close();
        header("Location: suppressionCarte.php");
        exit();
    }

    // Afficher le formulaire de confirmation
    echo "<form method='post' onsubmit='return confirmSuppression();'>";
    echo "<input type='hidden' name='confirm' value='1'>";
    echo "<input type='submit' value='Supprimer'>";
    echo "</form>";

    // Ajouter le script JavaScript pour personnaliser le message de confirmation
    echo "<script>
            function confirmSuppression() {
                return confirm('Êtes-vous sûr de vouloir supprimer ?');
            }
          </script>";
    }
?>
