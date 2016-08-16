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



		        if (isset($_SESSION['ID']) AND isset($_SESSION['login']))
			{
			echo 'Bonjour '.$_SESSION['login']."<br/>";

			//On récupère l'ID des trajets su conducteur en ligne
			$reponse = $bdd->prepare('SELECT depart, arrivee, datehoraire, nbplaces, ID FROM trajets WHERE ID_conducteur = :ID_conducteur AND effectue != 1');
			$reponse->execute(array('ID_conducteur' => $_SESSION['ID']));
			echo "Les trajets que vous proposez sont: <br/>";
			while ($donnees = $reponse->fetch())
			{
			    echo "ID du trajet: <strong>".$donnees['ID']."</strong><br/>";
			    echo "Ville de départ: ".$donnees['depart']."<br/>";
			    $_SESSION['depart'] = $donnees['depart'];
			    //Prend à chaque fois la valeur du dernier de la liste, c'est un PROBLÈME
			    echo "Ville d'arrivee: ".$donnees['arrivee']."<br/>";
			    $_SESSION['arrivee'] = $donnees['arrivee'];
			    echo "Date et heure: ".$donnees['datehoraire']."<br/>";
			    $_SESSION['datehoraire'] = $donnees['datehoraire'];
			    echo "Nombre de places restantes: ".$donnees['nbplaces']."<br/>";


			    //On récupère l'ID du passager
			    $req = $bdd->prepare('SELECT ID_passager FROM reservations WHERE ID_trajet = :ID_trajet');
			    $req->execute(array('ID_trajet' => $donnees['ID']));
			    while ($donneesBis = $req->fetch())
			    {
				$IDpassagers = $donneesBis['ID_passager'];
				$reqbis = $bdd->prepare('SELECT login FROM membres WHERE ID = :ID');
			    	$reqbis->execute(array('ID' => $IDpassagers));
				    while ($donneesBisBis = $reqbis->fetch())
				    {
					$loginPassagers = $donneesBisBis['login'];
					echo "<strong>Passager:</strong> ".$loginPassagers." ";
				    }
				    $reqbis->closeCursor();
			    }
			    $req->closeCursor();
			
			    //echo "<br/><a href='annulationConducteur_action.php'>Je souhaite annuler cette proposition contre une pénalité de 10 euros à payer à chaque passager.</a><br/>";

			   
		 	    echo "<br/><br/>";

			}

					
			$reponse->closeCursor();


			}


			echo "<form method='POST' action='annulationConducteur_action.php'>";
			$req = $bdd->prepare('SELECT ID FROM trajets WHERE ID_conducteur = :ID_conducteur AND effectue != 1');
			$req->execute(array('ID_conducteur' => $_SESSION['ID']));
			echo "<label for='choixTrajAnn'><br/><br/>ID du trajet à supprimer: </label>";
			echo "<select id='choixTrajAnn' name='choixTrajAnn'>"; 
			while ($donnees = $req->fetch())
			{
			   echo "<option>".$donnees['ID']."</option>";
			}
			$req->closeCursor();
			echo "</select>";
			echo "<input type='submit' value='Supprimer le trajet' />";
			echo "</form>";



			echo "<form method='POST' action='effectue_action.php'>";
			$req = $bdd->prepare('SELECT ID FROM trajets WHERE ID_conducteur = :ID_conducteur AND effectue != 1');
			$req->execute(array('ID_conducteur' => $_SESSION['ID']));
			echo "<label for='choixTrajEff'><br/><br/>ID du trajet à déclarer effectué: </label>";
			echo "<select id='choixTrajEff' name='choixTrajEff'>"; 
			while ($donnees = $req->fetch())
			{
			   echo "<option>".$donnees['ID']."</option>";
			}
			$req->closeCursor();
			echo "</select>";
			echo "<input type='submit' value='Déclarer effectué' />";
			echo "</form>";






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
