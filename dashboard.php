<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
session_start();

if(isset($_GET["action"]) && $_GET["action"] == "logout"){
	session_destroy();
	header("Location: login.php");
	exit;
}

if(empty($_SESSION['idUser'])){
	header("Location: login.php");
}

try {
    $bdd = new PDO('mysql:host=localhost;dbname=covid_app', "bat", "aaazzz42");
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

$reqFlux = $bdd->prepare("SELECT * from flux order by dateIn desc LIMIT 15");
$reqFlux->execute();
$fluxs = $reqFlux->fetchAll();


$getChartData = $bdd->prepare("SELECT date(dateIn), count(*) from flux group by date(dateIn) order by date(dateIn) asc");
$getChartData->execute();

$dataPoints = [];
while ($data = $getChartData->fetch()){

	$dataPoints[] = [
		"y" => $data["count(*)"],
		"label" => strftime('%d %B', strtotime($data["date(dateIn)"]))
	];
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
    <title>Panel d'administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js" type="text/javascript"></script>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/dashboard/">

    

    <!-- Bootstrap core CSS -->
<link href="/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

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
    <link href="css/admin.css" rel="stylesheet">
  </head>
  <body>
    
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Brasserie les Bains</a>
  
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link" href="dashboard.php?action=logout">Log out</a>
    </li>
  </ul>
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">
              <span data-feather="home"></span>
              Tableau de bord
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="detail.php">
              <span data-feather="file"></span>
              Tous les fluxs
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Tableau de bord</h1>
      </div>

      <!--<canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>-->
      <div id="myChart" class="my-4 w-100" style="width: 900px; height: 380px"></div>

      <h2>Derniers fluxs</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>Nom</th>
              <th>Pr??nom</th>
              <th>Mobile</th>
              <th>Heure d'entr??e</th>
              <th>Heure de sortie</th>
            </tr>
          </thead>
          <tbody>

        	<?php foreach ($fluxs as $flux): ?>

	            <tr>
	            <td><?= $flux["nomClient"] ?></td>
	            <td><?= $flux["prenomClient"] ?></td>
	            <td><?= $flux["mobileClient"] ?></td>
	            <td><?= $flux["dateIn"] ?></td>
	            <td><?= $flux["dateOut"] ?></td>
	            </tr>
       
			<?php endforeach; ?>

          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>
	<script>
	window.onload = function () {
	 
	var chart = new CanvasJS.Chart("myChart", {
		data: [{
			type: "line",
			dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
		}]
	});
	chart.render();
	 
	}
	</script>

    <script src="/docs/5.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
    <!--<script src="https://getbootstrap.com/docs/5.0/examples/dashboard/dashboard.js"></script>-->
  </body>
</html>
