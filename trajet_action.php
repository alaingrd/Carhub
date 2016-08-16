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


		        if (isset($_SESSION['ID']) AND isset($_SESSION['login']))
			{

				$req = $bdd->prepare('INSERT INTO trajets(depart, arrivee, datehoraire, nbplaces, prix, ID_conducteur) VALUES(:depart, :arrivee, :datehoraire, :nbplaces, :prix, :ID_conducteur)');
$req->execute(array(
    'depart' => $_POST['depart'],
    'arrivee' => $_POST['arrivee'],
    'datehoraire' => $_POST['datehoraire'],
    'nbplaces' => $_POST['nbplaces'],
    'prix' => $_POST['prix'],
    'ID_conducteur' => $_SESSION['ID']
    ));

				echo 'Le trajet a bien été ajouté !<br/>';

				echo "<form method='post' action='connexion_action.php'>";
				echo '<input type="hidden" name="pass" value="' . htmlspecialchars($_SESSION['pass']) . '" />'."\n";
				echo '<input type="hidden" name="login" value="' . htmlspecialchars($_SESSION['login']) . '" />'."\n";
				echo "<input type='submit' value='Accueil' />";
				echo "</form>";
			}

			?>
		</div><!-- #contenu -->
	</div><!-- #centre -->

	<div id="pied">
		<p><strong>voiturehub.fr© Tous droits réservés</strong> UV LO07</p>
		<p id="copyright">
			Mise en page &copy; 2008
			<a href="deconnexion.php">Se déconnecter</a> et
			<a href="http://www.alsacreations.com">Alsacréations</a>
		</p>
	</div><!-- #pied -->

</div><!-- #global -->

</body>
