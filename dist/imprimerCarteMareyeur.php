<?php
    session_start();

    // Paramètres de connexion à la base de données MySQL
    $dbHost = "localhost";
    $dbUser = "root";
    $dbMdp = "";
    $dbName = "cartemetier";

    // Connexion à la base de données
    $conn = mysqli_connect($dbHost, $dbUser, $dbMdp, $dbName);

    // Vérifier si la connexion a échoué
    if (!$conn) {
        die("La connexion à la base de données a échoué : " . mysqli_connect_error());
    }

    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];

        // Requête SQL pour récupérer le prénom et le nom de l'utilisateur
        $requete = "SELECT PrenomUtilisateur, NomUtilisateur FROM Utilisateur WHERE EmailUtilisateur = '$email'";
        $resultat = mysqli_query($conn, $requete);

        // Vérifier si la requête a retourné un résultat
        if (mysqli_num_rows($resultat) == 1) {
            $utilisateur = mysqli_fetch_assoc($resultat);
            $prenomUtilisateur = $utilisateur['PrenomUtilisateur'];
            $nomUtilisateur = $utilisateur['NomUtilisateur'];
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

    // Définir la localisation en français
    setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

    // Obtenir la date actuelle
    $date = new DateTime();

    // Formater la date selon le format souhaité
    $formattedDate = strftime('%A %d %B %Y, %Hh%M', $date->getTimestamp());

    // Récupération des données depuis la base de données
    $showForm = true;
    $selectedCardData = null;
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $numeroCarte = $_POST["numero_carte"];
    
        // Récupération des données de la carte sélectionnée depuis la base de données
        $query = "SELECT cm.idCarteMareyeur, cm.NumeroCarte, cm.LibelleCategorie, cm.DateQuitance, cm.Siege, cm.DateDebutValid,
                         p.NomPersonne, p.PrenomPersonne, p.CNI,
                         e.Denomination, e.AdresseEntreprise, e.RegionEntreprise,
                         e.Activite, e.NumRegistreCom,
                         r.RenewalYear
                  FROM cartemareyeur cm
                  LEFT JOIN entreprise e ON cm.idEntreprise = e.idEntreprise
                  LEFT JOIN personne p ON e.idPersonne = p.idPersonne
                  LEFT JOIN renouvellement r ON r.idCarteMareyeur = cm.idCarteMareyeur
                  WHERE cm.NumeroCarte = '$numeroCarte'";
    
        $result = $conn->query($query);
    
        if ($result->num_rows > 0) {
            $selectedCardData = $result->fetch_assoc();
            $showForm = false;
        }
    }

    // Traitement du formulaire de recherche de carte
    if (isset($_POST['search_numero_carte'])) {
        $search_numero_carte = $_POST['search_numero_carte'];

        // Ajouter une clause WHERE à la requête SQL pour rechercher le numéro de carte spécifique
        $sql .= " WHERE cm.NumeroCarte = '$search_numero_carte'";
    }

    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impression de carte - Gestion des cartes de mareyeur</title>
    
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    
<link rel="stylesheet" href="assets/vendors/simple-datatables/style.css">

    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/logo-dpm.png" type="image/x-icon">


    <style>

.overlay-form {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(255, 255, 255, 0.8);
    padding: 0px;
    width: 80%;
    border-radius: 5px;
}

.overlay-text {
    position: absolute;
    top: 78%;
    left: 48%;
    color: black;
    transform: translate(-50%, -50%);
    padding: 0px;
    width: 100%;
    border-radius: 5px;
}

.overlay-item {
    margin: 0;
    font-size: 14px;
}


span {
    font-weight: bold;
}

p1 {
    position: absolute;
    top: -35%;
    left: 75%;
    transform: translate(-50%, -50%);
    border-radius: 5px;
    font-weight: bold;
    font-size: 15px;
    color: black;
}

p2 {
    position: absolute;
    top: -05%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 5px;
    font-weight: bold;
    font-size: 16px;
    color: black;
}

valid {
    position: absolute;
    top: -160%;
    left: 85%;
    transform: translate(-50%, -50%);
    border-radius: 5px;
    font-weight: bold;
    font-size: 24px;
    color: black;
}

.person-photo {
    position: absolute;
    top: -40px;
    right: 300px;
}

.person-photo img {
    max-width: 100px;
    max-height: 100px;
    border-radius: 50%;
}


@page {
    size: 85.60mm 53.98mm;
    margin: 0;
}

</style>
<script>
    function printCard() {
        var printContents = document.querySelector('.card').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
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
                <h3>Imprimer carte</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class='breadcrumb-header'>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="tableau_de_bord.php">Tableau de bord</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Liste des mareyeurs</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
            <div class="row">
                <?php if ($showForm) : ?>
                    <!-- Formulaire de sélection de carte à imprimer -->
                    <form method="post">
                        <div class="customize-input">
                            <label for="numero_carte"></label>
                            <input class="form-control custom-shadow custom-radius border-0 bg-white" placeholder="Saisir numéro de carte" aria-label="Saisir numéro de carte" type="text" id="numero_carte" name="numero_carte" required>
                            <i class="form-control-icon" data-feather="search"></i>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        <div class="card">

        <div class="row">
                    <div class="col-12">
                        <!-- Row -->
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <!-- Card -->
                                <div class="card position-relative">
                                    <img class="card-img-top img-fluid" src="assets/images/bg-cartes/recto.jpg" alt="Card image cap" >
                                    <div class="card-body overlay-text">
                                        <?php if (!$showForm && $selectedCardData) : ?>
                                            <!-- Affichage de la photo -->
                                            <div class="person-photo">
                                                <img src="<?php echo $selectedCardData["Photo"]; ?>" alt="Photo" max-width: 50px; max-height: 50px;>
                                            </div>
                                            <!-- Affichage des données de la carte sélectionnée -->
                                            <p2><?php echo $selectedCardData["LibelleCategorie"]; ?></p2>
                                            <p1>N°<?php echo $selectedCardData["NumeroCarte"]; ?></p1>
                                            <p class="overlay-item">Prénom  : <span><?php echo $selectedCardData["PrenomPersonne"]; ?></span></p>
                                            <p class="overlay-item">Nom     : <span><?php echo $selectedCardData["NomPersonne"]; ?></span></p>
                                            <p class="overlay-item">N° cni  : <span><?php echo $selectedCardData["CNI"]; ?></span></p>
                                            <p class="overlay-item">Siège   : <span><?php echo $selectedCardData["Siege"]; ?></span></p>
                                            <!-- Ajoutez d'autres champs ici -->
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!-- Card -->
                            </div>
                        </div>
                        <!-- Row -->
                    </div>
                </div>
                <!-- End Row -->
                <!-- Row -->
                <div class="row">
                    <div class="col-12">
                        <!-- Row -->
                        <div class="row">
                            <!-- column -->
                            <div class="col-lg-3 col-md-6">
                                <!-- Card -->
                                <div class="card">
                                    <img class="card-img-top img-fluid" src="assets/images/bg-cartes/verso.jpg" alt="Card image cap">
                                    <div class="card-body overlay-text">
                                        <?php if (!$showForm && $selectedCardData) : ?>
                                            <!-- Affichage de la validité -->
                                            <valid><?php echo $selectedCardData["RenewalYear"]; ?></valid>
                                            <!-- Ajoutez d'autres champs ici -->
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!-- Card -->
                            </div>
                            <!-- column -->
                        </div>
                        <!-- Row -->
                    </div>
                </div>
                <!-- End Row -->

        </div>
                    <div>
                        <button id="print-button" onclick="printCard()">Imprimer</button>
                    </div>

    </section>
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
    
<script src="assets/vendors/simple-datatables/simple-datatables.js"></script>
<script src="assets/js/vendors.js"></script>

    <script src="assets/js/main.js"></script>
</body>
</html>
