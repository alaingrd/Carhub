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

			
			//Consultation de la base de données
			$req = $bdd->prepare('SELECT argent FROM membres WHERE login = :login');
			$req->execute(array(
			    'login' => $_POST['login']));

			$resultat = $req->fetch();



		        if (isset($_SESSION['ID']) AND isset($_SESSION['login']))
			{
			echo 'Bonjour '.$_SESSION['login'].",<br/>";
			echo "<form method='POST' action='creationConversation.php'>";
				
			$reponse = $bdd->prepare('SELECT login FROM membres WHERE ID != :ID');
			$reponse->execute(array('ID' => $_SESSION['ID']));

			echo "<label for='interloc'>Choix d'un interlocuteur: </label>";
			echo "<select id='interloc' name='interloc'>";
			while ($donnees = $reponse->fetch())
			{
			    echo "<option>".$donnees['login']."</option>";
			}

			echo "<input type='submit' value='Valider' />";
			
			echo "</form>";			
			$reponse->closeCursor();
			}
			
			?>
		</div><!-- #contenu -->
	</div><!-- #centre -->

	<div id="pied">
		<?php
		echo "<form method='post' action='connexion_action.php'>";
				echo '<input type="hidden" name="pass" value="' . htmlspecialchars($_SESSION['pass']) . '" />'."\n";
				echo '<input type="hidden" name="login" value="' . htmlspecialchars($_SESSION['login']) . '" />'."\n";
				echo "<input type='submit' value='Accueil' />";
		echo "</form>";
		?>
		<p><strong><a href="deconnexion.php">[SE DÉCONNECTER]</a></strong></p>
		<p id="copyright">
			<strong>voiturehub.fr© Tous droits réservés</strong> UV LO07
		</p>
	</div><!-- #pied -->

</div><!-- #global -->

</body>
