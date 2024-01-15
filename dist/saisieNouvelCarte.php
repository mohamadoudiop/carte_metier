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
                <h3>Saisie nouvelle carte</h3>
                <p class="text-subtitle text-muted"></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class='breadcrumb-header'>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="tableau_de_bord.php">Tableau de bord</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Saisie nouvelle carte</li>
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
                    <form method="POST" action="actionSaisieNouvelCarte.php">
                        <div class="card-header">
                            <h4 class="card-title">Infos personne physique</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Prénom</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" id="prenom" class="form-control" name="prenom"
                                                placeholder="Prénom" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Nom</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" id="nom" class="form-control" name="nom"
                                                placeholder="Last Name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Date de naissance</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="date" id="datenaissance" class="form-control" name="datenaissance"
                                                placeholder="Date de naissance">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Lieu de naissance</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" id="lieunaissance" class="form-control" name="lieunaissance"
                                                placeholder="Lieu de naissance">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">CNI</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="number" id="cni" class="form-control" name="cni"
                                                placeholder="CNI" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Date de délivrance</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="date" id="datedelivrance" class="form-control" name="datedelivrance"
                                                placeholder="Date de délivrance" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Adresse</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" id="adresse" class="form-control" name="adresse"
                                                placeholder="Adresse">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <h4 class="col-form-label">Genre</h4>
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="genre" value="Homme" id="genre" checked>
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    Homme
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="genre" value="Femme" id="genre">
                                                <label class="form-check-label" for="flexRadioDefault2">
                                                    Femme
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Profession</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" id="profession" class="form-control" name="profession"
                                                placeholder="Profession">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Téléphone</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" id="telephone" class="form-control" name="telephone"
                                                placeholder="Téléphone">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <p>Photo</p>
                                    <div class="form-file">
                                        <input type="file" class="form-file-input" id="photo" name="photo">
                                        <label class="form-file-label" for="customFile">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <!-- Aperçu de la photo sélectionnée -->
                                        <img id="photo-preview" src="#" alt="Aperçu de la photo" style="display: none; max-width: 150px; max-height: 150px;">
                                    </div>
                                </div>
                            </div>
                        <div class="card-header">
                            <h4 class="card-title">Infos personne morale</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Dénomination</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" id="denomination" class="form-control" name="denomination"
                                                placeholder="Dénomination">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <h6>Selectionner la région</h6>
                                            <div class="form-group">
                                                <select class="choices form-select" id="region" name="region">
                                                    <option value="">Selectionnez la région</option>
                                            <!-- Les régions seront remplies dynamiquement -->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <h6>Selectionner d'abord la région</h6>
                                            <div class="form-group">
                                                <select class="choices form-select" id="departement" name="departement">
                                                    <option value="">Selectionnez d'abord la région</option>
                                        <!-- Les départements seront remplies dynamiquement en fonction de la région sélectionnée -->
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Adresse entreprise</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" id="adresseEntreprise" class="form-control" name="adresseEntreprise"
                                                placeholder="Adresse entreprise">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Activité</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" id="activiteEntreprise" class="form-control" name="activiteEntreprise"
                                                placeholder="Activité">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Téléphone</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="number" id="telephoneEntreprise" class="form-control" name="telephoneEntreprise"
                                                placeholder="Téléphone entreprise">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row align-items-center">
                                        <div class="col-lg-2 col-3">
                                            <label class="col-form-label">Numéro Registre De Commerce</label>
                                        </div>
                                        <div class="col-lg-10 col-9">
                                            <input type="text" id="numRegistreCom" class="form-control" id="numRegistreCom" name="numRegistreCom"
                                                placeholder="Numéro Registre De Commerce" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-t-15">
                            <button class="btn btn-primary" type="submit">Enregistrer</button>
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
