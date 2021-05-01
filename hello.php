<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!empty($_COOKIE['idUser'])){
	header("Location: login.php");
}

try {
    $bdd = new PDO('mysql:host=localhost;dbname=covid_app', "bat", "aaazzz42");
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}


if (isset($_POST['send'])){
	if(!empty($_POST['nom']) and !empty($_POST['prenom']) and !empty($_POST['mobile']) and !empty($_POST['date']) and !empty($_POST['time'])){

		$nom = strtoupper(htmlspecialchars($_POST['nom']));
		$prenom = ucfirst(strtolower(htmlspecialchars($_POST['prenom'])));
		$mobile = htmlspecialchars($_POST['mobile']);
		$date = new DateTime(htmlspecialchars($_POST['date']));
		$time = new DateTime(htmlspecialchars($_POST['time']));
		$dateTimeIn = new DateTime($date->format('Y-m-d') . ' ' . $time->format('H:i'));
		$dateTimeOut = new DateTime();
		date_add($dateTimeOut, date_interval_create_from_date_string('1 hour'));



		$addFlux = $bdd->prepare("INSERT INTO flux(nomClient, prenomClient, mobileClient, dateIn, dateOut) VALUES (?, ?, ?, ?, ?)");
		$addFlux->execute(array($nom, $prenom, $mobile, $dateTimeIn->format('Y-m-d H:i'), $dateTimeOut->format('Y-m-d H:i'))) or die(print_r($bdd->errorInfo()));

		$id = $bdd->lastInsertId();

		setcookie('user_id', "$id", time()+3600*24, '/', '', false, true);
		header("Location: thanks.php");

	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Brasserie les Bains</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="css/index.css">

</head>
<body>
	<div id="app">
		<div class="py-5 text-center">
	      <img class="d-block mx-auto mb-4" src="https://getbootstrap.com/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
	      <h2>Brasserie des Bains</h2>
	      <p class="lead">Les données fournis seront détruites sous 7 jours. Elles se seront en aucun cas utilisées à des fins commerciales.</p>
    	</div>
		<form method="post" action="hello.php">
			<div class="row g-3" id="formulaire">
	            <div class="col-sm-6">
	              <label for="firstName" class="form-label">Prénom</label>
	              <input type="text" class="form-control" id="firstName" placeholder="" value="" required="" name="prenom">
	              <div class="invalid-feedback">
	                Un prénom valide est requis.
	              </div>
	            </div>

	            <div class="col-sm-6">
	              <label for="lastName" class="form-label">Nom</label>
	              <input type="text" class="form-control" id="lastName" placeholder="" value="" required="" name="nom">
	              <div class="invalid-feedback">
	                Un nom valide est requis.
	              </div>
	            </div>

	            <div class="col-md-3">
	              <label for="phone" class="form-label">Mobile</label>
	              <input type="tel" class="form-control" id="phone" placeholder="06XXXXXXXX" required="" pattern="[0]{1}[67]{1}[0-9]{8}" name="mobile">
	              <div class="invalid-feedback">
	                Numero de téléphone requis.
	              </div>
	            </div>

	            <div class="col-md-3">
	              <label for="date" class="form-label">Date</label>
	              <input type="date" class="form-control" id="date" placeholder="" required="" name="date" value="<?= date("Y-m-d")?>" max="<?= date("Y-m-d")?>">
	              <div class="invalid-feedback">
	                Une date valide est requise.
	              </div>
	            </div>

	            <div class="col-md-3">
	              <label for="time" class="form-label">Heure</label>
	              <input type="time" class="form-control" id="time" placeholder="" required="" name="time" value="<?= date("H:i")?>" max="<?= date("H:i")?>">
	              <div class="invalid-feedback">
	                Une heure valide est requise.
	              </div>
	            </div>

	            <button class="w-100 btn btn-primary btn-lg" type="submit" name="send">Valider</button>
	          </div>
		</form>
	</div>



</body>
</html>