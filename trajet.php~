<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta charset="UTF-8">
	<title>
		Voiturehub.fr: Trouvez le covoiturage qu'il vous faut
	</title>
	<!-- La feuille de styles "base.css" doit être appelée en premier. -->
	<link rel="stylesheet" type="text/css" href="base.css" media="all" />
	<link rel="stylesheet" type="text/css" href="index.css" media="screen" />
</head>

<body>

<div id="global">

	<div id="entete">
		<h1>
			<img alt="" src="http://upload.wikimedia.org/wikipedia/commons/thumb/7/7f/Autoroute_icone.svg/64px-Autoroute_icone.svg.png" />
			<span><a href='index.html'>VOITUREHUB.FR</a></span>
		</h1>
		<p class="sous-titre">
			Trouvez le covoiturage qu'il vous faut parmi nos annonces !
			<?php echo "<br/>"; ?>
			<span id="date_heure"></span>
			<script type="text/javascript">window.onload = date_heure('date_heure');</script>
		</p>
	</div><!-- #entete -->

	<div id="centre">

		<div id="contenu">
			<h3>Espace conducteur</h3>
			<?php
			session_start();
			//Connexion à la base de données
			try
			{
				$bdd = new PDO('mysql:host=localhost; dbname=carhub; charset=utf8', 'root', 'motdepasse');
			}
			catch(Exception $e)
			{
				die('Erreur :'.$e->getMessage());
			}

			
			//Consultation de la base de données
			$req = $bdd->prepare('SELECT argent FROM membres WHERE login = :login');
			$req->execute(array(
			    'login' => $_POST['login']));

			$resultat = $req->fetch();


		        if (isset($_SESSION['ID']) AND isset($_SESSION['login']))
			{
			    echo 'Bonjour '.$_SESSION['login']."<br/>";
			    echo "<form method='POST' action='trajet_action.php'>";


			    echo "<label id='depart'>Veuillez saisir la ville de départ:</label>";
			    echo "<input type='text' id='depart' name='depart' /><br/>";
			    echo "<label id='arrivee'>Veuillez saisir la ville d'arrivée:</label>";
			    echo "<input type='text' id='arrivee' name='arrivee' /><br/>";
			    echo "<label id='datehoraire'>Veuillez saisir la date et l'heure du départ sous le format suivant AAAA-MM-JJ HH:MM:SS:</label>";
			    echo "<input type='text' id='datehoraire' name='datehoraire' /><br/>";
			    echo "<label id='nbplaces'>Veuillez saisir le nombre de places disponibles:</label>";
			    echo "<input type='text' id='nbplaces' name='nbplaces' /><br/>";

			    echo "<label id='prix'>Veuillez saisir le prix par passager:</label>";
			    echo "<input type='text' id='prix' name='prix' /><br/>";

			    echo "<input type='submit' value='Valider' />";
			    echo "<input type='reset' value='Effacer' />";

			    echo "</form>";
			}
			
			?>
		</div><!-- #contenu -->
	</div><!-- #centre -->

	<div id="pied">
		<p><a href="deconnexion.php">[SE DÉCONNECTER]</a></p>
		
		<form method="post" action="connexion_action.php" />
		<?php echo "<input type='hidden' name='pass' value='" . $_SESSION['pass'] . " />"."\n"; ?>
		<?php echo "<input type='hidden' name='login' value='" . $_SESSION['login']. " />"."\n"; ?>
		<p><a href="connexion_action.php">[PRÉCÉDENT]</a></p>
		</form>
		<p id="copyright">
			<strong>voiturehub.fr© Tous droits réservés</strong> UV LO07
		</p>
	</div><!-- #pied -->

</div><!-- #global -->

</body>
