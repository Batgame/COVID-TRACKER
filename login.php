<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


try {
    $bdd = new PDO('mysql:host=localhost;dbname=covid_app', "bat", "aaazzz42");
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

if(isset($_POST['connect'])){
  if(!empty($_POST['username']) and !empty($_POST['password'])){

    $username = ucfirst(strtolower(htmlspecialchars($_POST['username'])));
    $password = htmlspecialchars($_POST['password']);

    $reqUser = $bdd->prepare("SELECT * from users where username = ?");
    $reqUser->execute(array($username));

    $results = $reqUser->fetch(PDO::FETCH_ASSOC);

    if ($results){
      if(password_verify($password, $results['password'])){

        session_start();
        $_SESSION['idUser'] = $results['id'];
        $_SESSION['username'] = $username;

        header("Location: dashboard.php");

        $erreur = "ok";
      } else {
        $erreur = "Mauvais identifiant/mot de passe.";
      }
    } else {
      $erreur = "Mauvais identifiant/mot de passe";
    }
  } else {
    $erreur = "Veuillez saisir tous les champs.";
  }
}

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.80.0">
    <title>Connexion</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <!-- Bootstrap core CSS -->
  <link href="/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <meta name="theme-color" content="#7952b3">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="css/login.css" rel="stylesheet">
  </head>
  <body class="text-center">
    
<main class="form-signin">
  <form method="post" action="login.php">
    <img class="mb-4" src="https://getbootstrap.com/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
    <h1 class="h3 mb-3 fw-normal">Authentification requise</h1>
    <label for="inputEmail" class="visually-hidden">Nom d'utilisateur</label>
    <input type="text" id="inputEmail" class="form-control" placeholder="Nom d'utilisateur" required autofocus name="username">
    <label for="inputPassword" class="visually-hidden">Mot de passe</label>
    <input type="password" id="inputPassword" class="form-control" placeholder="Mot de passe" required name="password">
   
    <button class="w-100 btn btn-lg btn-primary" type="submit" name="connect">S'authentifier</button>
  </form>
  <?php
    if(isset($erreur)){
      echo "<br/>" . $erreur;
    }
  ?>
</main>
  </body>
</html>
