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
			<h3>Espace passager</h3>
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
/*
			$req = $bdd->prepare('SELECT depart, arrivee, datehoraire FROM trajets WHERE login = :login');
			$req->execute(array(
			    'login' => $_SESSION['ID']));

			$resultat = $req->fetch(); 
*/



		        if (isset($_SESSION['ID']) AND isset($_SESSION['login']))
			{
			echo 'Bonjour '.$_SESSION['login']."<br/>";



			$reponse = $bdd->prepare('SELECT ID_trajet, ID_conducteur, nbplacesRes FROM reservations WHERE ID_passager = :ID');
			$reponse->execute(array('ID' => $_SESSION['ID']));
			while ($donnees = $reponse->fetch())
			{
				$IDtrajet = $donnees['ID_trajet'];
				$IDconducteur = $donnees['ID_conducteur'];
				$nbplacesReservees = $donnees['nbplacesRes'];
					
				$req = $bdd->prepare('SELECT depart, arrivee, datehoraire, nbplaces, effectue FROM trajets WHERE ID = :ID');
				$req->execute(array('ID' => $donnees['ID_trajet']));
				echo "Les trajets que vous avez réservés sont: <br/>";
				while ($donneesBis = $req->fetch())
				{
				    echo "Ville de départ: ".$donneesBis['depart']."<br/>";
				    $_SESSION['depart'] = $donneesBis['depart'];
				    echo "Ville d'arrivee: ".$donneesBis['arrivee']."<br/>";
				    $_SESSION['arrivee'] = $donneesBis['arrivee'];
				    echo "Date et heure: ".$donneesBis['datehoraire']."<br/>";
				    $_SESSION['datehoraire'] = $donneesBis['datehoraire'];
				    echo "Nombre de places réservées: ".$donnees['nbplacesRes']."<br/>";
				    if ($donneesBis['effectue'] != 1) {
				    	echo "<br/><a href='annulation_action.php'>Je souhaite annuler cette réservation contre une pénalité de 10 euros</a><br/>";
			 	    echo "<br/><br/>";
				    }
				    else {
					echo "Ce trajet a déjà été effectué";
					echo "<br/><br/>";
				    }
				}
				$req->closeCursor();
			}
			$reponse->closeCursor();
			}
echo "<form method='post' action='connexion_action.php'>";
echo '<input type="hidden" name="pass" value="' . htmlspecialchars($_SESSION['pass']) . '" />'."\n";
echo '<input type="hidden" name="login" value="' . htmlspecialchars($_SESSION['login']) . '" />'."\n";
echo "<input type='submit' value='[ACCUEIL]' />";
echo "</form>";
			?>
		</div><!-- #contenu -->
	</div><!-- #centre -->

	<div id="pied">
		<p><strong><a href="deconnexion.php">[SE DÉCONNECTER]</a></strong></p>
		<p id="copyright">
			<strong>voiturehub.fr© Tous droits réservés</strong> UV LO07
		</p>
	</div><!-- #pied -->

</div><!-- #global -->

</body>
