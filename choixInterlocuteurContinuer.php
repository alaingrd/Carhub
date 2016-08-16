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
			<h3>Messagerie</h3>
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
			
			//On récupère le login de celui en ligne (celui qui contacte)
			$req = $bdd->prepare('SELECT login FROM membres WHERE ID = :id');
			$req->execute(array('id' => $_SESSION['ID']));
			while ($donnees = $req->fetch())
			{
			    $login = $donnees['login'];
			}
			$req->closeCursor();




			echo "<form method='POST' action='messagerie_action.php'>";
				
			//On fait deux requêtes simulatément pour éviter que l'utilisateur puisse s'envoyer lui-même un message
/*
			$reponse = $bdd->prepare('SELECT login1, login2 FROM conversations WHERE login1 = :login OR login2 = :login');
*/

			$reponse = $bdd->prepare('SELECT login1 FROM conversations WHERE login2 = :login');
			$reponse->execute(array('login' => $login));

			$reponseBIS = $bdd->prepare('SELECT login2 FROM conversations WHERE login1 = :login');
			$reponseBIS->execute(array('login' => $login));


			echo "<label for='interloc'>Choix d'un interlocuteur: </label>";
			echo "<select id='interloc' name='interloc'>";
			while ($donnees = $reponse->fetch())
			{
			    echo "<option>".$donnees['login1']."</option>";
			}
			while ($donneesBIS = $reponseBIS->fetch())
			{
			    echo "<option>".$donneesBIS['login2']."</option>";
			}

			echo "</select>";
			echo "<input type='submit' value='Valider' />";
			
			echo "</form>";			
			$reponse->closeCursor();
			}
			
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
