<?php
session_start();

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['email'])) {
    // Rediriger vers la page d'accueil si l'utilisateur est déjà connecté
    header("Location: dist/tableau_de_bord.php");
    exit();
}

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

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $email = $_POST["email"];
    $motDePasse = $_POST["mdp"];

    // Échapper les caractères spéciaux pour éviter les injections SQL
    $email = mysqli_real_escape_string($conn, $email);
    $motDePasse = mysqli_real_escape_string($conn, $motDePasse);

    // Requête SQL pour vérifier les informations de connexion
    $requete = "SELECT * FROM Utilisateur WHERE EmailUtilisateur = '$email' AND MDPUtilisateur = '$motDePasse'";
    $resultat = mysqli_query($conn, $requete);

    // Vérifier si la requête a retourné un résultat
    if (mysqli_num_rows($resultat) == 1) {
        // Informations de connexion valides, récupérer le prénom et le nom de l'utilisateur
        $utilisateur = mysqli_fetch_assoc($resultat);
        $_SESSION['email'] = $utilisateur['EmailUtilisateur'];
        $_SESSION['prenom'] = $utilisateur['PrenomUtilisateur'];
        $_SESSION['nom'] = $utilisateur['NomUtilisateur'];

        header("Location: dist/tableau_de_bord.php");
        exit();
    } else {
        // Informations de connexion invalides, afficher un message d'erreur
        $erreurMessage = "Identifiant ou mot de passe incorrect.";
    }
}

// Fermer la connexion à la base de données
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter - Gestion des cartes mareyeurs</title>
    <link rel="stylesheet" href="dist/assets/css/bootstrap.css">
    
    <link rel="shortcut icon" href="dist/assets/images/logo-dpm.png" type="image/x-icon">
    <link rel="stylesheet" href="dist/assets/css/app.css">
</head>

<body>
    <div id="auth">
        
<div class="container">
    <div class="row mt-5">
        <div class="col-md-5 col-sm-12 mx-auto">
            <div class="card pt-4">
                <div class="card-body">
                    <div class="text-center mb-5">
                        <img src="dist/assets/images/logo-dpm.png" height="98" class='mb-4'>
                        <h3>Se connecter</h3>
                        <p>Veuillez vous connecter pour continuer.</p>
                    </div>
                    <form method="POST" action="index.php">
                        <div class="form-group position-relative has-icon-left">
                            <label for="username">Nom d'utilisateur</label>
                            <div class="position-relative">
                                <input type="email" class="form-control" name="email" id="email" placeholder="nom@mpem.gouv.sn">
                                <div class="form-control-icon">
                                    <i data-feather="user"></i>
                                </div>
                            </div>
                        </div>
                        <div class="form-group position-relative has-icon-left">
                            <div class="clearfix">
                                <label for="password">Mot de passe</label>
                                <a href="auth-forgot-password.html" class='float-end'>
                                    <small>Mot de passe oublié?</small>
                                </a>
                            </div>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="password" name="mdp">
                                <div class="form-control-icon">
                                    <i data-feather="lock"></i>
                                </div>
                            </div>
                        </div>

                        <div class='form-check clearfix my-4'>
                            <div class="checkbox float-start">
                                <input type="checkbox" id="checkbox1" class='form-check-input' >
                                <label for="checkbox1">Se souvenir de moi</label>
                            </div>
                        </div>
                        <div class="clearfix">
                            <button class="btn btn-primary float-end">Se connecter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
    <script src="dist/assets/js/feather-icons/feather.min.js"></script>
    <script src="dist/assets/js/app.js"></script>
    
    <script src="dist/assets/js/main.js"></script>
</body>

</html>
