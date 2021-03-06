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
/* ICI ID_conducteur EST $_SESSION['ID'] !!!!!!!!! */
//nbplacesRes = NOMBRE DE PLACES RÉSERVÉES, PAS RESTANTES !!!!!!!
			//Tout doit partir de cette requête pour que tous les passagers puissent être dédommagés

			$IDAnn = $_POST['choixTrajAnn'];
			
			

			//On récupère le nombre de places et le prix du  trajet
			$req = $bdd->prepare('SELECT nbplaces, prix FROM trajets WHERE ID = :ID');
			$req->execute(array('ID' => $IDAnn));
			while ($donneesBis = $req->fetch())
			{
				$nbplaces = $donneesBis['nbplaces'];
				$prixtrajet = $donneesBis['prix'];
			}	
			$req->closeCursor();


			//On récupère l'ID du passager
			$reponse = $bdd->prepare('SELECT ID_trajet, ID_passager, nbplacesRes FROM reservations WHERE ID_conducteur = :ID');
			$reponse->execute(array('ID' => $_SESSION['ID']));
			while ($donnees = $reponse->fetch())
			{
				$IDpassager = $donnees['ID_passager'];
				$nbplacesReservees = $donnees['nbplacesRes'];
			}
			$reponse->closeCursor();


			//On récupère la somme d'argent dont disposait le passager
			$reponseBis = $bdd->prepare('SELECT argent FROM membres WHERE ID = :ID');
			$reponseBis->execute(array('ID' => $IDpassager));
			while ($donneesBis = $reponseBis->fetch())
			{
				$ancargentPass = $donneesBis['argent'];
			}	
			$reponseBis->closeCursor();

			//On restitue ce que le passager a payé + 10 euros
			$req = $bdd->prepare('UPDATE membres SET argent = :nvargent WHERE ID = :id_passager');
			$req->execute(array(
		    	'nvargent' => $ancargentPass + $prixtrajet*$nbplacesReservees + 10,
		    	'id_passager' => $IDpassager));			
			$req->closeCursor();

			
			//On récupère la somme d'argent dont disposait le conducteur
			$reponseBis = $bdd->prepare('SELECT argent FROM membres WHERE ID = :ID');
			$reponseBis->execute(array('ID' => $_SESSION['ID']));
			while ($donneesBis = $reponseBis->fetch())
			{
				$ancargentCond = $donneesBis['argent'];
			}	
			$reponseBis->closeCursor();


			//On prend l'argent qu'avait reçu le conducteur et 10 euros supplémentaires
			$req = $bdd->prepare('UPDATE membres SET argent = :nvargent WHERE ID = :id_conducteur');
			$req->execute(array(
		    	'nvargent' => $ancargentCond - $prixtrajet*$nbplacesReservees - 10,
		    	'id_conducteur' => $_SESSION['ID']));			
			$req->closeCursor();




							

			//On envoie une notification à chaque passager
			$req = $bdd->prepare('INSERT INTO notifications(ID_concerne, type, notification) VALUES(:ID_concerne, :type, :notification)');
			$req->execute(array(
			    'ID_concerne' => $IDpassager,
			    'type' => 'ANNULATION D\'UN CONDUCTEUR',
			    'notification' => "Le trajet ".$_SESSION['depart']."-".$_SESSION['arrivee']." proposé par ".$_SESSION['login']." pour le ".$_SESSION['datehoraire']." a été annulé. En conséquence, la somme de ".$prixtrajet*$nbplacesReservees." euros vous a été rembousée, incluant un bonus de 10 euros."
			    ));
			$req->closeCursor();
			


			//On supprime le trajet dans la table trajets
			$req = $bdd->prepare('DELETE FROM trajets WHERE ID= :IDTrajet');
			$req->execute(array('IDTrajet' => $IDAnn));			
			$req->closeCursor();

			//On supprime la réservation dans la table reservations
			$req = $bdd->prepare('DELETE FROM reservations WHERE ID_trajet= :IDTrajet');
			$req->execute(array('IDTrajet' => $IDAnn));			
			$req->closeCursor();




			echo "Vous venez d'annuler un trajet.<br/>";
			echo "Vous venez de rembourser l'argent reçu et un pénalité de 10 euros par passager<br/>";


			/*
			$reponse = $bdd->prepare('SELECT ID_trajet, ID_passager, nbplacesRes FROM reservations WHERE ID_conducteur = :ID');
			$reponse->execute(array('ID' => $_SESSION['ID']));
			while ($donnees = $reponse->fetch())
			{
				$IDtrajet = $donnees['ID_trajet'];
				$IDpassager = $donnees['ID_passager'];
				$nbplacesReservees = $donnees['nbplacesRes'];
			
				//On récupère le nombre de places et le prix du  trajet
				$req = $bdd->prepare('SELECT nbplaces, prix FROM trajets WHERE ID = :ID');
				$req->execute(array('ID' => $IDtrajet));
				while ($donneesBis = $req->fetch())
				{
					$nbplaces = $donneesBis['nbplaces'];
					$prixtrajet = $donneesBis['prix'];
				}	
				$req->closeCursor();

				//On récupère la somme d'argent dont disposait le passager
				$reponseBis = $bdd->prepare('SELECT argent FROM membres WHERE ID = :ID');
				$reponseBis->execute(array('ID' => $IDpassager));
				while ($donneesBis = $reponseBis->fetch())
				{
					$ancargentPass = $donneesBis['argent'];
				}	
				$reponseBis->closeCursor();

				

				//On restitue ce que le passager a payé + 10 euros
				$req = $bdd->prepare('UPDATE membres SET argent = :nvargent WHERE ID = :id_passager');
				$req->execute(array(
			    	'nvargent' => $ancargentPass + $prixtrajet*$nbplacesReservees + 10,
			    	'id_passager' => $IDpassager));			
				$req->closeCursor();

				//On récupère la somme d'argent dont disposait le conducteur
				$reponseBis = $bdd->prepare('SELECT argent FROM membres WHERE ID = :ID');
				$reponseBis->execute(array('ID' => $_SESSION['ID']));
				while ($donneesBis = $reponseBis->fetch())
				{
					$ancargentCond = $donneesBis['argent'];
				}	
				$reponseBis->closeCursor();


				//On prend l'argent qu'avait reçu le conducteur et 10 euros supplémentaires
				$req = $bdd->prepare('UPDATE membres SET argent = :nvargent WHERE ID = :id_conducteur');
				$req->execute(array(
			    	'nvargent' => $ancargentCond - $prixtrajet*$nbplacesReservees - 10,
			    	'id_conducteur' => $_SESSION['ID']));			
				$req->closeCursor();


				$type='ANNULATION D\'UN CONDUCTEUR';
				//$notif="Un trajet ".$_SESSION['login']." a été annulé. En conséquence, la somme de ".$prixtrajet*$nbplacesReservees." euros vous a été rembousée, incluant un bonus de 10 euros";
				$notif='Une somme vous a été remboursée.';				

				//On envoie une notification à chaque passager
				$req = $bdd->prepare('INSERT INTO notifications(ID_concerne, type, notification) VALUES(:ID_concerne, :type, :notification)');
				$req->execute(array(
				    'ID_concerne' => $IDpassager,
				    'type' => 'ANNULATION D\'UN CONDUCTEUR',
				    'notification' => "Le trajet ".$_SESSION['depart']."-".$_SESSION['arrivee']." proposé par ".$_SESSION['login']." pour le ".$_SESSION['datehoraire']." a été annulé. En conséquence, la somme de ".$prixtrajet*$nbplacesReservees." euros vous a été rembousée, incluant un bonus de 10 euros."
				    ));
				$req->closeCursor();

			}	
			$reponse->closeCursor();

			//On supprime le trajet dans la table trajets
			$req = $bdd->prepare('DELETE FROM trajets WHERE ID= :IDTrajet');
			$req->execute(array('IDTrajet' => $IDtrajet));			
			$req->closeCursor();

			//On supprime la réservation dans la table reservations
			$req = $bdd->prepare('DELETE FROM reservations WHERE ID_trajet= :IDTrajet');
			$req->execute(array('IDTrajet' => $IDtrajet));			
			$req->closeCursor();




			echo "Vous venez d'annuler un trajet.<br/>";
			echo "Vous venez de rembourser l'argent reçu et un pénalité de 10 euros par passager<br/>";
*/

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
