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
        $query = "SELECT cm.NumeroCarte, cm.LibelleCategorie, cm.DateQuitance, cm.Siege, cm.DateDebutValid,
                         p.NomPersonne, p.PrenomPersonne, p.CNI,
                         e.Denomination, e.AdresseEntreprise, e.RegionEntreprise,
                         e.Activite, e.NumRegistreCom
                  FROM cartemareyeur cm
                  LEFT JOIN entreprise e ON cm.idEntreprise = e.idEntreprise
                  LEFT JOIN personne p ON e.idPersonne = p.idPersonne
                  WHERE cm.NumeroCarte = '$numeroCarte'";
    
        $result = $conn->query($query);
    
        if ($result->num_rows > 0) {
            $selectedCardData = $result->fetch_assoc();
            $showForm = false;
        }
    }
    
    // Fermeture de la connexion à la base de données
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des cartes - Gestion des cartes de mareyeur</title>
    
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    
<link rel="stylesheet" href="assets/vendors/simple-datatables/style.css">

    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="shortcut icon" href="assets/images/logo-dpm.png" type="image/x-icon">
    <link rel="stylesheet" media="all" href="/assets/css/application-be836f5c9dfcd32cb1db16212d054715.css" data-turbolinks-track="true" />
    <script src="/assets/application-be27406b5ff34f5750a99b62859207c0.js" data-turbolinks-track="true"></script>
    <link href='/assets/css/fonts.googleapis.com_css_family=Open+Sans' rel='stylesheet' type='text/css'>

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
                            
                            
                            <li class="sidebar-item  ">

                                <a href="saisieNouvelCarte.php" class='sidebar-link'>
                                    <i data-feather="layout" width="20"></i> 
                                    <span>Ajouter nouvelle carte</span>
                                </a>
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
                            
                            <li class='sidebar-title'>Authentication</li>
                            
                            
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
    
        <div class="container">

            <div class="clearfix"></div>

            <div class="panel panel-default dataTables_wrapper">
                <div class="panel-heading"> 
                    <h3 class="panel-title">
                            Carte de mareyeur - Recto/Verso 
                    </h3>
                    <span class="pull-right btn-group">
                        <!--p></p-->
                        <p><a class="btn btn-warning" onclick="printDiv(&#39;printableArea&#39;,108715)" href="#"><i class="fa fa-print"></i> Imprimer </a></p>
                    </span>
                    <div class="clearfix"></div>
                </div>
            <div class="panel-body" id="printableArea">
                        
            <style type="text/css" media="print">
            @page
            {
                size: auto;   /* auto is the current printer page size */
                margin: 0;  /* this affects the margin in the printer settings */
            }

            body
            {
                background-color:#FFFFFF;
                border: solid 1px black ;
                margin: 0px;  /* the margin on the content before printing */
            }
            label{
                margin-bottom: 0;
            }

                /*.carte_titre{*/
                    /*font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;*/
                    /*font-weight: bold;*/
                /*}*/
            </style>
            <div class="col-md-6" style="margin-bottom: 5px"> 
                <div id="recto" class="col-md-12  page-break" style="position: relative">
                    <img style="position: absolute; top: 0; left: 0; width: 100%; height: 100%" src="/assets/bgd-carte-mareyeur.jpg" alt="recto vierge" />
                    <div style="position: absolute; top: 59px;width: 82%">
                        <div class="col-md-5" style="padding-left: 0; margin-top: 5px">
                            <span> Immatriculation N°: </span>
                        </div>
                        <div class="col-md-7">
                            <h3 style="margin-top:4px;position: relative; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold; text-align: center">
                            ZG-6180-KF
                            </h3>
                        </div>
                        <div style="line-height: 17px">
                            <label><span style="font-style: italic"> Nom pirogue : </span><br>
                                <span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold">DAVID-BA</span>
                            </label><br>
                            <label><span style="font-style: italic">Propriétaire : </span><br>
                                <span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold">
                                    ARAMATA MANGA 
                                </span>
                            </label><br>
                            <label><span style="font-style: italic">Activité (s) : </span><br>
                                    <span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold">Pêche</span>
                            </label><br>
                            <label><span style="font-style: italic">Date de délivrance : </span><br>
                                <span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold">18/08/2023</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="col-md-6">
                <div id="verso" class="col-md-12 page-break" style="position: relative;">
                    <img style="position: absolute; top: 0; left: 0; width: 100%; height: 100%" src="/assets/verso-carte-mareyeur.jpg" alt="verso vierge" />
                    <div style="position: absolute; top: 20px">
                        <h6 style="margin-top:55px; position: relative; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold">
                            Adresse du centre d'émission :
                        </h6>
                        <div>
                            <address>
                            <span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold"> </span><br>
                            <i class="fa fa-phone-square"></i> (+221) 33 991 13 09
                            </address>
                            <qrcode style="position:absolute; top:0; left:0; right:0; margin-top: 80px; margin-left: 300px">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkAQAAAABYmaj5AAAAsUlEQVR4nLVUwQ3DQAiDLIA3YP+x2AAmoHCvRP3ElcrHQsIycPiuvkVeco//ZKqKwgGKZ91igQVOfXTgB+iu63XlM2tJntd59AYoXqmKCxYo3r531AGuz4LueEXuU5FpHkBwvLQC0iuFuxeEZ9Vokn22JQw6mvS7d80uA9w+jxEwY5J3vUboGCbpo61Xl9Q3E31l7sbey0TuYbN9jo5lRgind4xgWMvTfphOO9n/5afsA+1UXa1/BpLQAAAAAElFTkSuQmCC" />
                            <h8 style="margin-left: 20px; font-size: 8px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-weight: bold"> ICELABOSOFT </h8> 
                            </qrcode>
                        </div>
                    </div>
                    <footer style="text-align:center; position: absolute; margin-top:235px; margin-left:100px;"> <h6> Conception & Réalisation par FJ2M&copy; </h6></footer>
                </div>
            </div>
	  	</div>
	</div>

    <div class="footer">            
        <footer>
            <div class="footer clearfix mb-0 text-muted">
                <div class="float-start">
                    <p>2023 &copy; Direction des Pêches maritimes</p>
                </div>
                <div class="float-end">
                    <p>Développé par le Bureau informatique</a></p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
