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

// Requête pour compter le nombre total de personnes enregistrées
$queryCountPersons = "SELECT COUNT(*) as totalPersons FROM personne";
$resultCountPersons = $conn->query($queryCountPersons);

$totalPersons = 0;
if ($resultCountPersons && $resultCountPersons->num_rows > 0) {
    $row = $resultCountPersons->fetch_assoc();
    $totalPersons = $row['totalPersons'];
}

// Requête pour compter le nombre total de cartes enregistrées
$queryCountCartes = "SELECT COUNT(*) as totalCartes FROM cartemareyeur";
$resultCountCartes = $conn->query($queryCountCartes);

$totalCartes = 0;
if ($resultCountCartes && $resultCountCartes->num_rows > 0) {
    $row = $resultCountCartes->fetch_assoc();
    $totalCartes = $row['totalCartes'];
}

// Requête pour compter le nombre de femmes
$queryCountFemales = "SELECT COUNT(*) as totalFemales FROM personne WHERE Genre = 'Femme'";
$resultCountFemales = $conn->query($queryCountFemales);

$totalFemales = 0;
if ($resultCountFemales && $resultCountFemales->num_rows > 0) {
    $row = $resultCountFemales->fetch_assoc();
    $totalFemales = $row['totalFemales'];
}

// Requête pour compter le nombre d'hommes
$queryCountMales = "SELECT COUNT(*) as totalMales FROM personne WHERE Genre = 'Homme'";
$resultCountMales = $conn->query($queryCountMales);

$totalMales = 0;
if ($resultCountMales && $resultCountMales->num_rows > 0) {
    $row = $resultCountMales->fetch_assoc();
    $totalMales = $row['totalMales'];
}

// Requête pour compter le nombre de 1er Catégorie
$queryCountCateg1 = "SELECT COUNT(*) as totalCategorie1 FROM cartemareyeur WHERE LibelleCategorie = '1ère Catégorie'";
$resultCountCateg1 = $conn->query($queryCountCateg1);

$totalCateg1 = 0;
if ($resultCountCateg1 && $resultCountCateg1->num_rows > 0) {
    $row = $resultCountCateg1->fetch_assoc();
    $totalCateg1 = $row['totalCategorie1'];
}

// Requête pour compter le nombre de 2ème Catégorie
$queryCountCateg2 = "SELECT COUNT(*) as totalCategorie2 FROM cartemareyeur WHERE LibelleCategorie = '2ème Catégorie'";
$resultCountCateg2 = $conn->query($queryCountCateg2);

$totalCateg2 = 0;
if ($resultCountCateg2 && $resultCountCateg2->num_rows > 0) {
    $row = $resultCountCateg2->fetch_assoc();
    $totalCateg2 = $row['totalCategorie2'];
}

// Requête pour compter le nombre de 3ème Catégorie
$queryCountCateg3 = "SELECT COUNT(*) as totalCategorie3 FROM cartemareyeur WHERE LibelleCategorie = 'Micro mareyage'";
$resultCountCateg3 = $conn->query($queryCountCateg3);

$totalCateg3 = 0;
if ($resultCountCateg3 && $resultCountCateg3->num_rows > 0) {
    $row = $resultCountCateg3->fetch_assoc();
    $totalCateg3 = $row['totalCategorie3'];
}

// Calculer le nombre total de cartes
$queryTotalCartes = "SELECT COUNT(*) AS totalCartes FROM cartemareyeur";
$resultTotalCartes = $conn->query($queryTotalCartes);
$rowTotalCartes = $resultTotalCartes->fetch_assoc();
$totalCartes = $rowTotalCartes['totalCartes'];

// Calculer les pourcentages
$percentageCateg1 = ($totalCateg1 / $totalCartes) * 100;
$percentageCateg2 = ($totalCateg2 / $totalCartes) * 100;
$percentageCateg3 = ($totalCateg3 / $totalCartes) * 100;

// Requête SQL pour la somme des montants par catégorie
$querySomme1ereCategorie = "SELECT SUM(MontantCategorie) AS somme_1ere_categorie FROM cartemareyeur WHERE LibelleCategorie = '1ère Catégorie'";
$querySomme2emeCategorie = "SELECT SUM(MontantCategorie) AS somme_2eme_categorie FROM cartemareyeur WHERE LibelleCategorie = '2ème Catégorie'";
$querySommeMicroMareyage = "SELECT SUM(MontantCategorie) AS somme_micro_mareyage FROM cartemareyeur WHERE LibelleCategorie = 'Micro mareyage'";

// Exécution de la requête
$resultSomme1ereCategorie = $conn->query($querySomme1ereCategorie);
$resultSomme2emeCategorie = $conn->query($querySomme2emeCategorie);
$resultSommeMicroMareyage = $conn->query($querySommeMicroMareyage);

// Vérification des résultats
if ($resultSomme1ereCategorie) {
    $row = $resultSomme1ereCategorie->fetch_assoc();
    $somme1ereCategorie = $row["somme_1ere_categorie"];
}
if ($resultSomme1ereCategorie) {
    $row = $resultSomme2emeCategorie->fetch_assoc();
    $somme2emeCategorie = $row["somme_2eme_categorie"];
}
if ($resultSomme1ereCategorie) {
    $row = $resultSommeMicroMareyage->fetch_assoc();
    $sommeMicroMareyage = $row["somme_micro_mareyage"];
}

// Définir la localisation en français
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

// Obtenir la date actuelle
$date = new DateTime();

// Formater la date selon le format souhaité
$formattedDate = strftime('%A %d %B %Y, %Hh%M', $date->getTimestamp());

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Gestion des cartes mareyeurs</title>
    
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    
    <link rel="stylesheet" href="assets/vendors/chartjs/Chart.min.css">

    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/logo-dpm.png" type="image/x-icon">
</head>
<body>
    <div id="app">
        <div id="sidebar" class='active'>
            <div class="sidebar-wrapper active">
    <div class="sidebar-header">
        <img src="assets/images/logo-dpm.png">
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
            <div class="page-header flex-wrap">
                <h3 class="mb-0"> Salut, bienvenue!</h3>
            </div>
<div class="main-content container-fluid">
    <div class="page-title">
        <h3>Tableau de bord</h3>
    </div>
    <section class="section">
        <div class="row mb-2">
            <div class="col-12 col-md-3">
                <div class="card card-statistic">
                    <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class='px-3 py-3 d-flex justify-content-between'>
                                <h3 class='card-title'>MAREYEURS</h3>
                                <div class="card-right d-flex align-items-center">
                                <h1 class="mb-0"><br><p><?php echo $totalPersons; ?> </p></h1>
                                </div>
                            </div>
                            <div class="chart-wrapper">
                                <canvas id="canvas1" style="height:100px !important"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card card-statistic">
                    <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class='px-3 py-3 d-flex justify-content-between'>
                                <h3 class='card-title'>CARTES</h3>
                                <div class="card-right d-flex align-items-center">
                                    <p><?php echo $totalCartes; ?> </p>
                                </div>
                            </div>
                            <div class="chart-wrapper">
                                <canvas id="canvas2" style="height:100px !important"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card card-statistic">
                    <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class='px-3 py-3 d-flex justify-content-between'>
                                <h3 class='card-title'>HOMMES</h3>
                                <div class="card-right d-flex align-items-center">
                                    <p><?php echo $totalMales; ?> </p>
                                </div>
                            </div>
                            <div class="chart-wrapper">
                                <canvas id="canvas3" style="height:100px !important"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card card-statistic">
                    <div class="card-body p-0">
                        <div class="d-flex flex-column">
                            <div class='px-3 py-3 d-flex justify-content-between'>
                                <h3 class='card-title'>FEMMES</h3>
                                <div class="card-right d-flex align-items-center">
                                    <p><?php echo $totalFemales; ?> </p>
                                </div>
                            </div>
                            <div class="chart-wrapper">
                                <canvas id="canvas4" style="height:100px !important"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>          
        <div class="row mb-2">
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                    <div>
                      <p class="mb-2 text-md-center text-lg-left"><h4>Micro maréyage</h4></p>
                      <h1 class="mb-0"><?php echo $totalCateg3; ?></h1><br>
                      <p class="mb-2 text-md-center text-lg-left">Total redevance</p>
                      <h1 class="mb-0"><?php echo $sommeMicroMareyage; ?> CFA</h1>
                    </div>
                    <i class="typcn typcn-briefcase icon-xl text-secondary"></i>
                  </div>
                  <canvas id="expense-chart" height="80"></canvas>
                </div>
              </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                    <div>
                      <p class="mb-2 text-md-center text-lg-left"><h4>1ère Catégorie</h4></p>
                      <h1 class="mb-0"><?php echo $totalCateg1; ?></h1><br>
                      <p class="mb-2 text-md-center text-lg-left">Total redevance</p>
                      <h1 class="mb-0"><?php echo $somme1ereCategorie; ?> CFA</h1>
                    </div>
                    <i class="typcn typcn-chart-pie icon-xl text-secondary"></i>
                  </div>
                  <canvas id="budget-chart" height="80"></canvas>
                </div>
              </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                    <div>
                      <p class="mb-2 text-md-center text-lg-left"><h4>2ème Catégorie</h4></p>
                      <h1 class="mb-0"><?php echo $totalCateg2; ?></h1><br>
                      <p class="mb-2 text-md-center text-lg-left">Total redevance</p>
                      <h1 class="mb-0"><?php echo $somme2emeCategorie; ?> CFA</h1>
                    </div>
                    <i class="typcn typcn-clipboard icon-xl text-secondary"></i>
                  </div>
                  <canvas id="balance-chart" height="80"></canvas>
                </div>
              </div>
            </div>
        </div>
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
    
    <script src="assets/vendors/chartjs/Chart.min.js"></script>
    <script src="assets/vendors/apexcharts/apexcharts.min.js"></script>
    <script src="assets/js/pages/dashboard.js"></script>

    <script src="assets/js/main.js"></script>
</body>
</html>
