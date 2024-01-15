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

    if (isset($_GET['idEntreprise'])) {
        $idEntreprise = $_GET['idEntreprise'];
    }
    // Connexion à la base de données
    $conn = connexion_base();

    // Vérifier si la connexion a échoué
    if (!$conn) {
        die("La connexion à la base de données a échoué : " . mysqli_connect_error());
    }

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        // Requête SQL pour récupérer le prénom et le nom de l'utilisateur
        $requete = "SELECT PrenomUtilisateur, NomUtilisateur, PhotoUtilisateur FROM Utilisateur WHERE EmailUtilisateur = '$email'";
        $resultat = mysqli_query($conn, $requete);

        // Vérifier si la requête a retourné un résultat
        if (mysqli_num_rows($resultat) == 1) {
            $utilisateur = mysqli_fetch_assoc($resultat);
            $prenomUtilisateur = $utilisateur['PrenomUtilisateur'];
            $nomUtilisateur = $utilisateur['NomUtilisateur'];
            $photoUtilisateur = $utilisateur['PhotoUtilisateur'];
        }
    } else {
        // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
        header("Location: http://localhost/cartemetier/index.php");
        exit();
    }

    // Traitement du formulaire de déconnexion
    if (isset($_POST['logout'])) {
        // Détruire toutes les variables de session
        session_unset();

        // Détruire la session
        session_destroy();

        // Rediriger vers la page de connexion
        header("Location: http://localhost/cartemetier/index.php");
        exit();
    }

    $dateFinValid = date("Y-12-31");
    
    // Requête pour vérifier s'il y a déjà des enregistrements dans la table "cartemareyeur"
    $sqlCheckTable = "SELECT COUNT(*) as count FROM cartemareyeur";
    $resultCheckTable = $conn->query($sqlCheckTable);

    if ($resultCheckTable && $resultCheckTable->num_rows > 0) {
        $row = $resultCheckTable->fetch_assoc();
        $rowCount = $row['count'];

        // Si la table contient des enregistrements, récupérez le dernier numéro alphanumérique
        if ($rowCount > 0) {
            // Requête pour obtenir le dernier numéro alphanumérique
            $sql = "SELECT NumeroCarte FROM cartemareyeur ORDER BY idCarteMareyeur DESC LIMIT 1";
            $resultat = $conn->query($sql);

            // Vérifier si la requête a réussi et s'il y a un résultat
            if ($resultat && $resultat->num_rows > 0) {
                $ligne = $resultat->fetch_assoc();
                $dernierNumero = $ligne['NumeroCarte'];
                // Séparer la partie fixe (par exemple, "DPM") de la partie numérique (par exemple, "000001")
                $partieFixe = substr($dernierNumero, 0, 3);
                $partieNumerique = substr($dernierNumero, 3);
                // Convertir la partie numérique en un nombre entier
                $nombreNumerique = intval($partieNumerique);
                // Incrémenter le nombre
                $nouveauNombreNumerique = $nombreNumerique + 1;
                // Utiliser sprintf pour formater le nombre avec des zéros à gauche
                $nouvellePartieNumerique = sprintf('%06d', $nouveauNombreNumerique);
                // Combiner la partie fixe et la nouvelle partie numérique pour obtenir le nouveau numéro alphanumérique
                $nouveauNumero = $partieFixe . $nouvellePartieNumerique;
            }
        } else {
            // Si aucune valeur n'a été insérée dans la table, définir un numéro de départ
            $nouveauNumero = "DPM000001";
        }
    } else {
        // En cas d'erreur lors de la vérification, définir également un numéro de départ par défaut
        $nouveauNumero = "DPM000001";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle carte - Gestion des cartes de mareyeur</title>
    
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    
    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/logo-dpm.png" type="image/x-icon">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function validateForm() {
            var prenom = document.getElementById("prenom").value;
            var nom = document.getElementById("nom").value;
            // Ajoutez d'autres champs ici si nécessaire

            if (prenom.trim() === '' || nom.trim() === '') {
                alert("Veuillez remplir tous les champs obligatoires.");
                return false; // Empêche l'envoi du formulaire
            }
            return true; // Permet l'envoi du formulaire
        }
        // Vérifier s'il y a un message de succès ou d'erreur à afficher
        <?php if (isset($successMessage)) : ?>
            alert("<?php echo $successMessage; ?>");
        <?php elseif (isset($errorMessage)) : ?>
            alert("<?php echo $errorMessage; ?>");
        <?php endif; ?>
    </script>
        
    <script>
        // Le script AJAX pour remplir les régions et les départements
        $(document).ready(function() {
            // Récupérer les régions et les ajouter dans le sélecteur
            $.ajax({
                url: "recuperer_regions.php",
                type: "POST",
                dataType: "json",
                success: function(data) {
                    var regionSelect = $("#region");
                    regionSelect.empty();
                    regionSelect.append("<option value=''>Sélectionnez une région</option>");
                    $.each(data, function(index, value) {
                        regionSelect.append("<option value='" + value + "'>" + value + "</option>");
                    });
                },
                error: function() {
                    alert("Erreur lors de la récupération des régions.");
                }
            });

            // Lorsque la région est sélectionnée, récupérer les départements correspondants
            $("#region").change(function() {
                var selectedRegion = $(this).val();

                // Envoyer une requête AJAX au fichier "recuperer_departements.php"
                $.ajax({
                    url: "recuperer_departements.php",
                    type: "POST",
                    data: { region: selectedRegion },
                    dataType: "json",
                    success: function(data) {
                        var departementSelect = $("#departement");
                        departementSelect.empty();
                        departementSelect.append("<option value=''>Sélectionnez d'abord une région</option>");
                        $.each(data, function(index, value) {
                            departementSelect.append("<option value='" + value + "'>" + value + "</option>");
                        });
                    },
                    error: function() {
                        alert("Erreur lors de la récupération des départements.");
                    }
                });
            });
        });
    </script>
</head>
<body>
    <!-- Afficher le message d'erreur -->
    <?php if (!empty($erreurEntrepriseId)) : ?>
        <div class="alert alert-danger"><?php echo $erreurEntrepriseId; ?></div>
    <?php endif; ?>
    <div id="app">
        <div id="sidebar" class='active'>
            <div class="sidebar-wrapper active">
    <div class="sidebar-header">
        <img src="assets/images/logo-dpm.png" alt="" srcset="">
    </div>
    <div class="sidebar-menu">
        <ul class="menu">
            
                
                <li class='sidebar-title'>Menu Principal</li>
                <li class="sidebar-item active ">
                    <a href="tableau_de_bord.php" class='sidebar-link'>
                        <i data-feather="home" width="20"></i> 
                        <span>Tableau de bord</span>
                    </a>
                </li>

                <li class='sidebar-title'>CARTE MAREYEUR</li>
                

                <li class="sidebar-item  has-sub">

                    <a href="#" class='sidebar-link'>
                        <i data-feather="briefcase" width="20"></i> 
                        <span>Ajouter cartes</span>
                    </a>
                    
                    <ul class="submenu ">
                        
                        <li>
                            <a href="saisieNouvelCarte.php"><span class="badge bg-success">Nouvelle carte</span></a>
                        </li>
                        
                        <li>
                            <a href="saisieAutreCarte.php"><span class="badge bg-success">Une autre carte</span></a>
                        </li>
                        
                    </ul>
                    
                </li>
                
                <li class="sidebar-item  ">

                    <a href="renouvelerCarte.php" class='sidebar-link'>
                        <i data-feather="layers" width="20"></i> 
                        <span>Renouveler une carte</span>
                    </a>
                </li>
                                
                <li class="sidebar-item  ">

                    <a href="modificationCarte.php" class='sidebar-link'>
                        <i data-feather="grid" width="20"></i> 
                        <span>Modifier une carte</span>
                    </a>
                </li>
                
            
                
                <li class="sidebar-item  ">

                    <a href="suppressionCarte.php" class='sidebar-link'>
                        <i data-feather="file-plus" width="20"></i> 
                        <span>Supprimer une carte</span>
                    </a>
                </li>
                
                <li class="sidebar-item  ">

                    <a href="imprimerCarteMareyeur.php" class='sidebar-link'>
                        <i data-feather="file-plus" width="20"></i> 
                        <span>Imprimer une carte</span>
                    </a>
                </li>
                
            
                
                <li class='sidebar-title'>STATISTIQUES</li>
                

                <li class="sidebar-item  ">

                    <a href="listeCarteMareyeur.php" class='sidebar-link'>
                        <i data-feather="layout" width="20"></i> 
                        <span>Liste des cartes</span>
                    </a>
                </li>
                
                <li class="sidebar-item  ">

                    <a href="listeRenouvellement.php" class='sidebar-link'>
                        <i data-feather="layers" width="20"></i> 
                        <span>Liste des renouvellements</span>
                    </a>
                </li>
                                
                <li class="sidebar-item  ">

                    <a href="listeMareyeurs.php" class='sidebar-link'>
                        <i data-feather="grid" width="20"></i> 
                        <span>Liste des mareyeurs</span>
                    </a>
                </li>
                
            
                
                <li class="sidebar-item  ">

                    <a href="listeEntreprise.php" class='sidebar-link'>
                        <i data-feather="file-plus" width="20"></i> 
                        <span>Liste des GIE</span>
                    </a>
                </li>
                
                <li class='sidebar-title'>Authentification</li>
                
                
                <li class="sidebar-item">
                    <a class='sidebar-link' href="logout.php">
                        <i data-feather="log-out" width="20"></i><span>Déconnexion</span>
                    </a>
                </li>
                
            
        </ul>
    </div>
    <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
</div>
        </div>
        <div id="main">
            <nav class="navbar navbar-header navbar-expand navbar-light">
                <a class="sidebar-toggler" href="#"><span class="navbar-toggler-icon"></span></a>
                <button class="btn navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav d-flex align-items-center navbar-light ms-auto">
                        <li class="dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                <div class="avatar me-1">
                                    <img src="assets/images/avatar/mmd.jpg" alt="" srcset="">
                                </div>
                                <div class="d-none d-md-block d-lg-inline-block">Bienvenue, <?php echo $prenomUtilisateur . ' ' . $nomUtilisateur; ?></div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#"><i data-feather="user"></i> Mon profil</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php"><i data-feather="log-out"></i> Déconnexion</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            
<div class="main-content container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Info nouvelle carte</h3>
                <p class="text-subtitle text-muted"></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class='breadcrumb-header'>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="tableau_de_bord.php">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="saisieNouvelCarte.php">Saisie Nouvelle Carte</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Info nouvelle carte</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
    </section>
    <!-- Basic Inputs end -->
        
    <!-- Horizontal Input start -->
    <section id="horizontal-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <form method="POST" action="actionAjoutAutreCarte.php">
                        <div class="card-body">
                            <div class="row">
                                <div class="card-body">
                                    <div class="col-md-6 mb-4">
                                        <h6>Selectionner la catégorie</h6>
                                        <div class="form-group">
                                            <select class="input--style-4" name="categorie" id="categorie" required>
                                                <option value="">Sélectionner une catégorie</option>
                                                <option value="1ère Catégorie">1ère Catégorie</option>
                                                <option value="2ème Catégorie">2ème Catégorie</option>
                                                <option value="Micro mareyeur">Micro mareyeur</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <div class="col-lg-2 col-3">
                                                <label class="col-form-label">Montant à payé</label>
                                            </div>
                                            <div class="col-lg-10 col-9">
                                                <input type="hidden" id="idEntreprise" class="form-control" name="idEntreprise" value="<?php echo $idEntreprise; ?>" >
                                                <input type="number" class="form-control" name="montantCategorie" id="montantCategorie" readonly
                                                    placeholder="Montant à payé">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Numéro carte</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" id="numeroCarte" class="form-control" name="numeroCarte" value="<?php echo $nouveauNumero; ?>" 
                                                placeholder="Numéro Carte" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Numéro quitance</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" id="numQuitance" class="form-control" name="numQuitance"
                                                placeholder="Numéro quitance">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Date Quitance</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="date" id="dateQuitance" class="form-control" name="dateQuitance""
                                                placeholder="Date Quitance">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Date début validité</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="date" id="dateDebutValid" class="form-control" name="dateDebutValid""
                                                placeholder="Date début validité">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Date fin validité</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="date" id="dateFinValid" class="form-control" name="dateFinValid"
                                               value="<?php echo $dateFinValid; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Siège</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" class="form-control" id="siege" name="siege"
                                                placeholder="Siège">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-t-15">
                                <button class="btn btn-primary" type="submit">Enregistrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Horizontal Input end -->
        
</div>

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2023 &copy; Direction des Pêches maritimes</p>
                    </div>
                    <div class="float-end">
                        <p>Développé par le Bureau informatique</a></p>
                    </div>
            </footer>
        </div>
    </div>
    <script src="assets/js/feather-icons/feather.min.js"></script>
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/app.js"></script>
    
    <script src="assets/js/main.js"></script>
    
    <!-- Jquery JS-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/datepicker/moment.min.js"></script>
    <script src="vendor/datepicker/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="js/global.js"></script>
    <div id="messageBox"></div>
        <script>
            // Fonction pour afficher les messages d'enregistrement réussi ou d'erreur
            function afficherMessage(message) {
                var messageBox = document.getElementById("messageBox");
                messageBox.innerHTML = message;
                messageBox.style.display = "block";
            }

            <?php
            // Affichez le message approprié à partir de PHP dans la partie JavaScript
            if (isset($successMessage)) {
                echo "afficherMessage('$successMessage');";
            } elseif (isset($errorMessage)) {
                echo "afficherMessage('$errorMessage');";
            }
            ?>
        </script>
    </div> 
    <script>
        // Obtenez les éléments de formulaire
        var categorieSelect = document.getElementById("categorie");
        var montantCategorieInput = document.getElementById("montantCategorie");

        // Associez un gestionnaire d'événements pour le changement de sélection de catégorie
        categorieSelect.addEventListener("change", function() {
            var selectedCategory = categorieSelect.value;
            var montant;

            // Assignez le montant en fonction de la catégorie sélectionnée
            switch (selectedCategory) {
                case "1ère Catégorie":
                    montant = 20000;
                    break;
                case "2ème Catégorie":
                    montant = 30000;
                    break;
                case "Micro mareyeur":
                    montant = 10000;
                    break;
                default:
                    montant = 0;
            }

            // Mettez à jour automatiquement le champ de montant
            montantCategorieInput.value = montant;
        });
    </script>
</body>
</html>
