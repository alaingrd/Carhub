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

			
			//On récupère le nom du login voulu
			$loginvoulu = $_POST['loginvoulu'];
			
			//On affiche quelques données le concernant se trouvant dans la table membres
			$req = $bdd->prepare('SELECT naissance, marque, modele, couleur, misenservice, note, nbnotes FROM membres WHERE login = :loginvoulu');
			$req->execute(array('loginvoulu' => $loginvoulu));
			while ($donnees = $req->fetch())
			{
			    $naissance = $donnees['naissance'];
			    $marque = $donnees['marque'];
			    $modele = $donnees['modele'];
			    $couleur = $donnees['couleur'];
			    $misenservice = $donnees['misenservice'];
			    $note = $donnees['note'];
			    $nbnotes = $donnees['nbnotes'];
			}
			$req->closeCursor();



			echo "<h3>Informations du profil de ".$loginvoulu." visibles par tous:</h3><br/>";
			echo "<ul>";
			echo "<li><strong>LOGIN: </strong> ".$loginvoulu."</li>";
			echo "<li><strong>ANNÉE DE NAISSANCE: </strong> ".$naissance."</li>";
			echo "<li><strong>MARQUE DU VÉHICULE: </strong> ".$marque."</li>";
			echo "<li><strong>MODÈLE DU VÉHICULE: </strong> ".$modele."</li>";
			echo "<li><strong>COULEUR DU VÉHICULE: </strong> ".$couleur."</li>";
			echo "<li><strong>ANNÉE DE MISE EN SERVICE DU VÉHICULE: </strong> ".$misenservice."</li>";
			echo "<li><strong>NOTE MOYENNE DU CONDUCTEUR: </strong> ".$note."/5</li>";
			echo "<li><strong>NOMBRES DE NOTES: </strong> ".$nbnotes."</li>";
			echo "</ul>";
			

			echo "<h3>Commentaires laissés sur ce conducteur: </h3>";

			//On récupère les commentaires précédents
			$reponse = $bdd->prepare('SELECT login_commentant, commentaire FROM commentaires WHERE login_commente = :login_commente ORDER BY ID_commentaire DESC');
			$reponse->execute(array('login_commente' => $loginvoulu));
			// Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)
			while ($donnees = $reponse->fetch())
			{
				echo '<p><strong>' . htmlspecialchars($donnees['login_commentant']) . '</strong> : ' . htmlspecialchars($donnees['commentaire']) . '</p>';
			}
			$reponse->closeCursor();

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
