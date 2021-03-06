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
			echo "Vous venez de réserver un trajet ".$_SESSION['depart']."-".$_SESSION['arrivee']." pour le ".$_SESSION['datehoraire']." et nous vous en remercions.<br/>";
			$montantTotal = $_POST['arrivee']*$_SESSION['prix'];
			echo "La somme de ".$montantTotal." euros vient d'être débitée de votre compte.<br/>";


			//On récupère la somme d'argent dont disposait le passager avant la transaction
			$req = $bdd->prepare('SELECT argent FROM membres WHERE ID = :id');
			$req->execute(array('id' => $_SESSION['ID']));
			while ($donnees = $req->fetch())
			{
			    $_SESSION['argent'] = $donnees['argent'];
			}
			$req->closeCursor();

			$_SESSION['argent'] = $_SESSION['argent'] - $montantTotal;
			echo "Vous disposez dorénavant de ".$_SESSION['argent']." euros.<br/>";

			//On met à jour dans la base de données le somme dont dispose le passager
			$req = $bdd->prepare('UPDATE membres SET argent = :nvargent WHERE ID = :id_passager');
			$req->execute(array(
			    'nvargent' => $_SESSION['argent'],
			    'id_passager' => $_SESSION['ID']));			
			$req->closeCursor();



			//On récupère la somme d'argent dont disposait le conducteur avant la transaction
			$req = $bdd->prepare('SELECT argent FROM membres WHERE ID = :id');
			$req->execute(array('id' => $_SESSION['id_conducteur']));
			while ($donnees = $req->fetch())
			{
			    $_SESSION['argentConducteur'] = $donnees['argent'];
			}
			$req->closeCursor();
			$IDconducteur = $_SESSION['id_conducteur'];

			//On met à jour dans la base de données le somme dont dispose le conducteur
			$req = $bdd->prepare('UPDATE membres SET argent = :nvargent WHERE ID = :id_conducteur');
			$req->execute(array(
			    'nvargent' => $montantTotal + $_SESSION['argentConducteur'],
			    'id_conducteur' => $_SESSION['id_conducteur']));			
			$req->closeCursor();
			


			//On diminue le nombre de places disponibles
			$nbplacesDispo = $_SESSION['nbplaces'] - $_POST['arrivee'];
			echo "Il reste ".$nbplacesDispo." places pour ce trajet.<br/>";
			$req = $bdd->prepare('UPDATE trajets SET nbplaces = :nvnbplaces WHERE ID_conducteur = :id_conducteur AND depart = :depart AND arrivee = :arrivee');
			$req->execute(array(
			    'nvnbplaces' => $nbplacesDispo,
			    'id_conducteur' => $_SESSION['id_conducteur'],
			    'depart' => $_SESSION['depart'],
			    'arrivee' => $_SESSION['arrivee']));			
			$req->closeCursor();

			//On récupère l'ID du trajet
			$req = $bdd->prepare('SELECT ID FROM trajets WHERE ID_conducteur = :id_conducteur AND depart = :depart AND arrivee = :arrivee');
			$req->execute(array(
			    'id_conducteur' => $_SESSION['id_conducteur'],
			    'depart' => $_SESSION['depart'],
			    'arrivee' => $_SESSION['arrivee']));
			while ($donnees = $req->fetch())
			{
			    $IDtrajet = $donnees['ID'];
			}
			$req->closeCursor();

			//On insère l'ID du trajet, l'ID du passager et l'ID du conducteur dans la table reservations. On fait une jointure
			$req = $bdd->prepare('INSERT INTO reservations(ID_trajet, ID_conducteur, ID_passager, nbplacesRes) VALUES(:ID_trajet, :ID_conducteur, :ID_passager, :nbplacesRes)');
			$req->execute(array(
			    'ID_trajet' => $IDtrajet,
			    'ID_conducteur' => $_SESSION['id_conducteur'],
			    'ID_passager' => $_SESSION['ID'],
			    'nbplacesRes' => $_POST['arrivee']));
			$req->closeCursor();



			//On envoie une notification au conducteur

			$req = $bdd->prepare('INSERT INTO notifications(ID_concerne, type, notification) VALUES(:ID_concerne, :type, :notification)');

			$req->execute(array(
			    'ID_concerne' => $IDconducteur,
			    'type' => 'RÉSERVATION D\'UN TRAJET',
			    'notification' => "L'utilisateur ".$_SESSION['login']." a réservé ".$_POST['arrivee']." places pour le trajet ".$_SESSION['depart']."-".$_SESSION['arrivee']." du ".$_SESSION['datehoraire']." que vous proposez."
			    ));

			$req->closeCursor();


			echo "<form method='post' action='connexion_action.php'>";
				echo '<input type="hidden" name="pass" value="' . htmlspecialchars($_SESSION['pass']) . '" />'."\n";
				echo '<input type="hidden" name="login" value="' . htmlspecialchars($_SESSION['login']) . '" />'."\n";
				echo "<input type='submit' value='[ACCUEIL]' />";
			echo "</form>";

			}
			?>
		</div><!-- #contenu -->
	</div><!-- #centre -->

	<div id="pied">
		<p><strong>voiturehub.fr© Tous droits réservés</strong> UV LO07</p>
		<p id="copyright">
			<a href="deconnexion.php">Se déconnecter</a>
		</p>
	</div><!-- #pied -->

</div><!-- #global -->

</body>
