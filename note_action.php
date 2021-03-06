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
			//On récupère la note actuelle et le nombre de notes

			$req = $bdd->prepare('SELECT note, nbnotes FROM membres WHERE login = :loginnote');
			$req->execute(array('loginnote' => $_POST['loginnote']));
			while ($donnees = $req->fetch())
			{
			    $note = $donnees['note'];
			    $nbnotes = $donnees['nbnotes'];
			}
			$req->closeCursor();
			
			$loginnote = $_POST['loginnote'];
			$_SESSION['loginnote'] = $loginnote;


			echo "Le conducteur ".$loginnote." a pour l'instant une note moyenne de ".$note."/5 sur une base de ".$nbnotes." notes.<br/>";

			echo "<form method='POST' action='note_action_post.php'>";
			echo "<label for='note'>Note que vous lui attribuez: </label>";
			echo "<select name='note'>";
			for ($i=0; $i<=5; $i++) {
				echo "<option>".$i."/5</option>";
			}
			echo "</select>";
			echo '<input type="hidden" name="ancnote" value="'.$note. '" />'."\n";
			echo '<input type="hidden" name="ancnbnotes" value="'.$nbnotes. '" />'."\n";

			echo "<input type='submit' value='Noter'/>";
			echo "</form>";

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
