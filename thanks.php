<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
try {
    $bdd = new PDO('mysql:host=localhost;dbname=covid_app', "bat", "aaazzz42");
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $bdd->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


if(!empty($_COOKIE['user_id'])){
    $userId = $_COOKIE['user_id'];

}else{
	header("Location: hello.php");
}

if(isset($_POST['sortir']))
{
	$updateFlux = $bdd->prepare("UPDATE flux SET dateOut = ? WHERE idFlux = ?");
	$updateFlux->execute(array(date('Y-m-d H:i'), $userId));

	setcookie('user_id', "", time()-3600, '/', '', false, true);
	header("Location: bye.php");

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
    <title>Brasserie les bains</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
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
      h1, p
      {
      	color: #006e2c;
      }
      .bg-light
      {
      	background-color: #ccf1db !important;
      }
      .btn-primary, .btn-primary:hover
      {
      	background-color: #f32302;
      	border-color: #f32302;
      }
    </style>
  </head>
  <body>
    
<main class="container">
  <div class="bg-light p-5 rounded mt-3">
    <h1>Vos informations ont bien été enregistrés !</h1>
    <p class="lead">N'oubliez pas de revenir pour nous dire quand vous partez !</p>
    <form method="post" action="thanks.php">
    	<button class="btn btn-lg btn-primary" name="sortir">Je m'en vais &raquo</button>
	</form>
  </div>
</main>
<script src="/docs/5.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

      
  </body>
</html>
