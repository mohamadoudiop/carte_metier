<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification de renouvellement - Gestion des cartes de mareyeur</title>
    
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    
    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/logo-dpm.png" type="image/x-icon">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    <?php
        function connexion_base() {
            $dbHost = "localhost";
            $dbName = "cartemetier";
            $dbUser = "root";
            $dbMdp = "";
            $conn = new mysqli($dbHost, $dbUser, $dbMdp, $dbName);
            return $conn;
        }

        if (isset($_GET['idCarteMareyeur'])) {
            $idCarteMareyeur = $_GET['idCarteMareyeur'];
            $conn = connexion_base();
            
            // Récupérer les données de la carte
            $queryRenouvellement1 = "SELECT * FROM cartemareyeur WHERE idCarteMareyeur = $idCarteMareyeur";
            $resultRenouvellement1 = $conn->query($queryRenouvellement1);

            
            // Récupérer les données du renouvellement associé
            $queryRenouvellement2 = "SELECT * FROM renouvellement WHERE idCarteMareyeur = (SELECT idCarteMareyeur FROM cartemareyeur WHERE idCarteMareyeur = $idCarteMareyeur)";
            $resultRenouvellement2 = $conn->query($queryRenouvellement2);
                        
            if ($resultRenouvellement1->num_rows > 0) {
                $rowRenouvellement1 = $resultRenouvellement1->fetch_assoc();
                $rowRenouvellement2 = $resultRenouvellement2->fetch_assoc();
    ?>
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
                
    <div class="main-content container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Modification de renouvellement</h3>
                    <p class="text-subtitle text-muted"></p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class='breadcrumb-header'>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="tableau_de_bord.php">Tableau de bord</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Modifier un renouvellement</li>
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
                        <form method="POST" action="traitementModifRenouvellement.php">
                            <div class="card-body">
                                <div class="card-header">
                                    <h4 class="card-title">Infos carte</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Numéro carte</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="hidden" id="idCarteMareyeur" name="idCarteMareyeur" value="<?= $rowRenouvellement1['idCarteMareyeur'] ?>">
                                                    <input class="form-control" id="numeroCarte" name="numeroCarte" value="<?= $rowRenouvellement1['NumeroCarte'] ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Catégorie</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input class="form-control" id="categorie" name="categorie" value="<?= $rowRenouvellement1['LibelleCategorie'] ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Numéro quitance</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input class="form-control" id="numQuitance" name="numQuitance" value="<?= $rowRenouvellement2['NumQuitance'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Date Quitance</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input class="form-control" id="dateQuitance" name="dateQuitance" value="<?= $rowRenouvellement2['DateQuitance'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Date début renouvellement</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input class="form-control" id="dateDebutRenouv" name="dateDebutRenouv" value="<?= $rowRenouvellement2['DateDebutRenouv'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Date fin validité</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input class="form-control" id="dateFinRenouv" name="dateFinRenouv" value="<?= $rowRenouvellement2['DateFinRenouv'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Montant renouvellement</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input class="form-control" id="montantRenouv" name="montantRenouv" value="<?= $rowRenouvellement2['MontantRenouv'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Année renouveler</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input class="form-control" id="annerenouvele" name="annerenouvele" value="<?= $rowRenouvellement2['RenewalYear'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="p-t-15">
                                <button class="btn btn-primary" type="submit">Enregistrer les modifications</button>
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- Horizontal Input end -->
            
    </div>           
    <?php
        } else {
            echo "Carte non trouvée.";
        }
        } else {
            echo "ID de carte non spécifié.";
        }
    ?>
    <?php
        if (isset($_GET['success'])) {
            echo "<p style='color: green;'>Les modifications ont été enregistrées avec succès.</p>";
        } elseif (isset($_GET['error'])) {
            echo "<p style='color: red;'>Erreur : méthode de requête invalide.</p>";
        }
    ?>
    <!-- Jquery JS-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/datepicker/moment.min.js"></script>
    <script src="vendor/datepicker/daterangepicker.js"></script>
    <!-- Main JS-->
    <script src="js/global.js"></script>


    <script src="assets/js/feather-icons/feather.min.js"></script>
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/app.js"></script>
    
    <script src="assets/js/main.js"></script>
</body>
</html>
