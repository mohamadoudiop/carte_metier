<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification carte - Gestion des cartes de mareyeur</title>
    
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    
    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/logo-dpm.png" type="image/x-icon">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        const photoInput = document.getElementById('photo');
        const photoPreview = document.getElementById('photo-preview');

        photoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.setAttribute('src', e.target.result);
                    photoPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                photoPreview.style.display = 'none';
            }
        });
    </script>

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

        if (isset($_GET['idCarteMareyeur'])) {
            $idCarteMareyeur = $_GET['idCarteMareyeur'];
            $conn = connexion_base();

            // Récupérer les données de la carte
            $queryCarte = "SELECT * FROM cartemareyeur WHERE idCarteMareyeur = $idCarteMareyeur";
            $resultCarte = $conn->query($queryCarte);
                
            // Récupérer les données de l'entreprise associée
            $queryEntreprise = "SELECT * FROM entreprise WHERE idEntreprise = (SELECT idEntreprise FROM cartemareyeur WHERE idCarteMareyeur = $idCarteMareyeur)";
            $resultEntreprise = $conn->query($queryEntreprise);
            
            // Récupérer les données de la personne associée
            $queryPersonne = "SELECT * FROM personne WHERE idPersonne = (SELECT idPersonne FROM entreprise WHERE idEntreprise = (SELECT idEntreprise FROM cartemareyeur WHERE idCarteMareyeur = $idCarteMareyeur))";
            $resultPersonne = $conn->query($queryPersonne);

            if ($resultCarte->num_rows > 0) {
                $rowCarte = $resultCarte->fetch_assoc();
                $rowEntreprise = $resultEntreprise->fetch_assoc();
                $rowPersonne = $resultPersonne->fetch_assoc();
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
                    <h3>Modification carte</h3>
                    <p class="text-subtitle text-muted"></p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class='breadcrumb-header'>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="tableau_de_bord.php">Tableau de bord</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Modififier carte</li>
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
                        <form method="POST" action="traitementModification.php">
                            <div class="card-body">
                                <div class="card-header">
                                    <h4 class="card-title">Infos carte</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Catégorie</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input class="form-control" id="libelleCategorie" name="libelleCategorie" value="<?= $rowCarte['LibelleCategorie'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Montant à payé</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="number" class="form-control" id="montantCategorie" name="montantCategorie" value="<?= $rowCarte['MontantCategorie'] ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Numéro carte</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="hidden" class="form-control" id="idCarteMareyeur" name="idCarteMareyeur" value="<?= $rowCarte['idCarteMareyeur'] ?>" required>
                                                    <input type="text" id="numeroCarte" class="form-control" name="numeroCarte" value="<?= $rowCarte['NumeroCarte'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Numéro quitance</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="text" id="numQuitance" class="form-control" name="numQuitance" value="<?= $rowCarte['NumQuitance'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Date Quitance</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="date" id="dateQuitance" class="form-control" name="dateQuitance" value="<?= $rowCarte['DateQuitance'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Date début validité</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="date" id="dateDebutValid" class="form-control" name="dateDebutValid"" value="<?= $rowCarte['DateDebutValid'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Date fin validité</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="date" id="dateFinValid" class="form-control" name="dateFinValid" value="<?= $rowCarte['DateFinValid'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Siège</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="text" class="form-control" id="siege" name="siege" value="<?= $rowCarte['Siege'] ?>"
                                                        placeholder="Siège">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="card-header">
                                            <h4 class="card-title">Infos personne physique</h4>
                                        </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Prénom</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="text" id="prenomPersonne" class="form-control" name="prenomPersonne" value="<?= $rowPersonne['PrenomPersonne'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Nom</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="text" id="nomPersonne" class="form-control" name="nomPersonne" value="<?= $rowPersonne['NomPersonne'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Date de naissance</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="date" id="dateNaissance" class="form-control" name="dateNaissance" value="<?= $rowPersonne['DateNaissance'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Lieu de naissance</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="text" id="lieuNaissance" class="form-control" name="lieuNaissance" value="<?= $rowPersonne['LieuNaissance'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">CNI</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="number" id="cni" class="form-control" name="cni" value="<?= $rowPersonne['CNI'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Date de délivrance</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="date" id="dateDelivranceCNI" class="form-control" name="dateDelivranceCNI" value="<?= $rowPersonne['DateDelivranceCNI'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Adresse</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="text" id="adressePersonne" class="form-control" name="adressePersonne" value="<?= $rowPersonne['AdressePersonne'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Genre</label>
                                                </div>
                                                    <div class="col-lg-10 col-9">
                                                        <input class="form-control" id="genre" name="genre" value="<?= $rowPersonne['Genre'] ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Profession</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="text" id="profession" class="form-control" name="profession" value="<?= $rowPersonne['Profession'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row align-items-center">
                                                <div class="col-lg-2 col-3">
                                                    <label class="col-form-label">Téléphone</label>
                                                </div>
                                                <div class="col-lg-10 col-9">
                                                    <input type="text" id="phonePersonne" class="form-control" name="phonePersonne" value="<?= $rowPersonne['PhonePersonne'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-2">
                                            <div class="input-group">
                                                <!-- Aperçu de la photo sélectionnée -->
                                                <img id="photo-preview" src="<?= $rowPersonne['Photo'] ?>" alt="Aperçu de la photo" style="display: none; max-width: 150px; max-height: 150px;">
                                            </div>
                                        </div>
                                    </div>
                                <div class="card-body">
                                    <div class="row">
                                    <div class="card-header">
                                        <h4 class="card-title">Infos personne morale</h4>
                                    </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <div class="col-lg-2 col-3">
                                                <label class="col-form-label">Dénomination</label>
                                            </div>
                                            <div class="col-lg-10 col-9">
                                                <input type="text" id="denomination" class="form-control" name="denomination" value="<?= $rowEntreprise['Denomination'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <div class="col-lg-2 col-3">
                                                <label class="col-form-label">Région</label>
                                            </div>
                                            <div class="col-lg-10 col-9">
                                                <input class="form-control" id="regionEntreprise" name="regionEntreprise" value="<?= $rowEntreprise['RegionEntreprise'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <div class="col-lg-2 col-3">
                                                <label class="col-form-label">Département</label>
                                            </div>
                                            <div class="col-lg-10 col-9">
                                                <input class="form-control" id="departementEntreprise" name="departementEntreprise" value="<?= $rowEntreprise['DepartementEntreprise'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <div class="col-lg-2 col-3">
                                                <label class="col-form-label">Adresse entreprise</label>
                                            </div>
                                            <div class="col-lg-10 col-9">
                                                <input type="text" id="adresseEntreprise" class="form-control" name="adresseEntreprise" value="<?= $rowEntreprise['AdresseEntreprise'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <div class="col-lg-2 col-3">
                                                <label class="col-form-label">Activité</label>
                                            </div>
                                            <div class="col-lg-10 col-9">
                                                <input type="text" id="activite" class="form-control" name="activite" value="<?= $rowEntreprise['Activite'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <div class="col-lg-2 col-3">
                                                <label class="col-form-label">Téléphone</label>
                                            </div>
                                            <div class="col-lg-10 col-9">
                                                <input type="number" id="phoneEntreprise" class="form-control" name="phoneEntreprise" value="<?= $rowEntreprise['PhoneEntreprise'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <div class="col-lg-2 col-3">
                                                <label class="col-form-label">Numéro Registre De Commerce</label>
                                            </div>
                                            <div class="col-lg-10 col-9">
                                                <input type="text" id="numRegistreCom" class="form-control" id="numRegistreCom" name="numRegistreCom" value="<?= $rowEntreprise['NumRegistreCom'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-t-15">
                                <button class="btn btn-primary" type="submit">Enregistrer les modifications</button>
                            </div>
                            <script>
                                const photoInput = document.getElementById('photo');
                                const photoPreview = document.getElementById('photo-preview');

                                photoInput.addEventListener('change', function(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                    photoPreview.setAttribute('src', e.target.result);
                                    photoPreview.style.display = 'block';
                                    };
                                    reader.readAsDataURL(file);
                                } else {
                                    photoPreview.style.display = 'none';
                                }
                                });
                            </script>
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
                    echo "<p style='color: red;'>Erreur : modifications non enregistrées.</p>";
                }
            ?>

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
</body>
</html>
