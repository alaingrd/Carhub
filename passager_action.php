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
			echo 'Bonjour '.$_SESSION['login'].",<br/>";


			//On récupère la somme d'argent dont dispose le passager pour savoir s'il a assez
			$req = $bdd->prepare('SELECT argent FROM membres WHERE ID = :id');
			$req->execute(array('id' => $_SESSION['ID']));
			while ($donnees = $req->fetch())
			{
			    $argentPassager = $donnees['argent'];
			}
			$req->closeCursor();
			//On récupère le prix du trajet et le nombre de places disponibles
			$req = $bdd->prepare('SELECT prix, nbplaces FROM trajets WHERE depart = :depart AND arrivee=:arrivee');
			$req->execute(array('depart' => $_SESSION['depart'], 'arrivee' => $_POST['arrivee']));
			while ($donnees = $req->fetch())
			{
			    $prixTrajet = $donnees['prix'];
			    $nbplacesDispo = $donnees['nbplaces'];
			}
			$req->closeCursor();


			if ($argentPassager < $prixTrajet)
			{
				echo "Vous ne disposez pas de suffisament d'argent sur votre compte.<br/>";
				echo "Il vous manque ".$prixTrajet-$argentPassager." euros.<br/>";
				echo "Vous pouvez aller recharger votre porte-monnaie électronique en suivant ce <a href='argent.php'>lien</a>.";
			}
			elseif ($nbplacesDispo == 0)
			{
				echo "Il n'y a plus de place pour ce trajet.<br/>";
				echo "Veuillez effecter une nouvelle recherche en modifiant vos critères.";
			}
			else
			{
//			echo "<form method='POST' action='reservation_action.php'>";

			$req = $bdd->prepare('SELECT nbplaces, prix, datehoraire, ID_conducteur FROM trajets WHERE depart = :depart AND arrivee=:arrivee AND nbplaces != 0');
			$req->execute(array('depart' => $_SESSION['depart'], 'arrivee' => $_POST['arrivee']));
			


			while ($donnees = $req->fetch())
			{

			    echo "<br/>Pour ce trajet ".$_SESSION['depart']."-".$_POST['arrivee'].", ";
			$_SESSION['arrivee'] = $_POST['arrivee'];
			    $id_conducteur = $donnees['ID_conducteur']; //Fonctionne
			    echo "il reste ".$donnees['nbplaces']." places. <br/>";
			    $_SESSION['nbplaces'] = $donnees['nbplaces'];
			    echo "Il aura lieu à la date et à l'heure suivante : ".$donnees['datehoraire']."<br/>";
			    $_SESSION['datehoraire'] = $donnees['datehoraire'];
			    echo "Le prix pour une personne est de ".$donnees['prix']." euros. <br/>";
			    $_SESSION['prix'] = $donnees['prix'];
/*
			    echo "Combien de places souhaitez-vous réserver ? ";
			    echo "<select id='arrivee' name='arrivee'>"; 
			    for ($i=1; $i<=$donnees['nbplaces']; $i++)
			    {
				echo "<option>".$i."</option>";
			    } 
			    echo "<input type='submit' value='Réserver' />";
*/
		            $reqbis = $bdd->prepare('SELECT marque, modele, couleur, misenservice, login FROM membres WHERE ID = :id');
			     $reqbis->execute(array('id' => $id_conducteur));
			     $_SESSION['id_conducteur'] = $id_conducteur;
			     while ($donneesBis = $reqbis->fetch())
			     {			
			     echo "Vous voyagerez dans une ".$donneesBis['marque']." ".$donneesBis['modele']." ".$donneesBis['couleur']." mise en service en ".$donneesBis['misenservice']." et conduite par l'utilisateur <strong>".$donneesBis['login']."</strong>.<br/>";
			     }
			     $reqbis->closeCursor();
			  }
			  $req->closeCursor();




			echo "<form method='POST' action='nbplaces_action.php'>";
			$req = $bdd->prepare('SELECT ID_conducteur FROM trajets WHERE depart = :depart AND arrivee = :arrivee');
			$req->execute(array(
			    'depart' => $_SESSION['depart'],
			    'arrivee' => $_SESSION['arrivee']));
			echo "<label for='choixCond'><br/><br/>Voyager avec: </label>";
			echo "<select id='choixCond' name='choixCond'>"; 
			while ($donnees = $req->fetch())
			{
			    $IDCond = $donnees['ID_conducteur'];
			    $reqbis = $bdd->prepare('SELECT login FROM membres WHERE ID = :ID');
			    $reqbis->execute(array('ID' => $IDCond));
			    while ($donneesBis = $reqbis->fetch()) 
				{
					echo "<option>".$donneesBis['login']."</option>";
				}
			
			}
			$req->closeCursor();
			echo "</select>";
			echo "<input type='submit' value='Réserver' />";
			echo "</form>";


			}
			
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
