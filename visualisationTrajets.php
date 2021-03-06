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
			<img alt="" src="picto/07.png" />
			<span><a href='index.html'>VOITUREHUB.FR</a></span>
		</h1>
		<p class="sous-titre">
			Trouvez le covoiturage qu'il vous faut parmi nos annonces !
		</p>
	</div><!-- #entete -->

	<div id="centre">

		<div id="contenu">
			<h3>Profil</h3>
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

			

			
			//On affiche toutes les données concernant un trajet depuis la tables trajets
			$req = $bdd->query('SELECT ID, depart, arrivee, datehoraire, nbplaces, prix, ID_conducteur FROM trajets WHERE effectue != 1');

			//$req->execute(array('loginvoulu' => $loginvoulu));
			while ($donnees = $req->fetch())
			{
			    $ID = $donnees['ID'];
			    $depart = $donnees['depart'];
			    $arrivee = $donnees['arrivee'];
			    $datehoraire = $donnees['datehoraire'];
			    $nbplaces = $donnees['nbplaces'];
			    $prix = $donnees['prix'];
			    $ID_conducteur = $donnees['ID_conducteur'];

			    //Récupérer login conducteur
			    $reqbis = $bdd->prepare('SELECT login FROM membres WHERE ID = :IDcond');
			    $reqbis->execute(array('IDcond' => $ID_conducteur));
			    while ($donneesBis = $reqbis->fetch())
			    {
				$loginCond = $donneesBis['login'];
			    }
			    $reqbis->closeCursor();

			echo "<h3>Informations complètes sur le trajet ".$depart."-".$arrivee." proposé par ".$loginCond.":</h3><br/>";

			echo "<ul>";
			echo "<li><strong>ID TRAJET: </strong> ".$ID."</li>";
			echo "<li><strong>VILLE DE DÉPART: </strong> ".$depart."</li>";
			echo "<li><strong>VILLE D'ARRIVÉE: </strong> ".$arrivee."</li>";
			echo "<li><strong>DATE ET HEURE: </strong> ".$datehoraire."</li>";
			echo "<li><strong>NOMBRE DE PLACES: </strong> ".$nbplaces."</li>";
			echo "<li><strong>PRIX: </strong> ".$prix."</li>";
			echo "<li><strong>LOGIN DU CONDUCTEUR: </strong> ".$loginCond."</li>";
			//Récupérer login conducteur
			$reqbis = $bdd->prepare('SELECT ID_passager FROM reservations WHERE ID_trajet = :ID_trajet');
		        $reqbis->execute(array('ID_trajet' => $ID));
			while ($donneesBis = $reqbis->fetch())
			{
	 		    $IDpass = $donneesBis['ID_passager'];

			    $reqbisbis = $bdd->prepare('SELECT login FROM membres WHERE ID = :ID');
			    $reqbisbis->execute(array('ID' => $IDpass));
			    while ($donneesBisBis = $reqbisbis->fetch())
			    {
				$loginPass = $donneesBisBis['login'];
				echo "<li><strong>LOGIN PASSAGER: </strong> ".$loginPass."</li>";
			    }
			    $reqbisbis->closeCursor();
			}
			$reqbis->closeCursor();

			echo "</ul>";


			}
			$req->closeCursor();


			echo "<form method='post' action='connexion_action.php'>";
			echo '<input type="hidden" name="pass" value="' . htmlspecialchars($_SESSION['pass']) . '" />'."\n";
			echo '<input type="hidden" name="login" value="' . htmlspecialchars($_SESSION['login']) . '" />'."\n";
			echo "<input type='submit' value='[ACCUEIL]' />";
			echo "</form>";
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
