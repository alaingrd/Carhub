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
			<h3>Finalisation de l'inscription</h3>
			<?php
			//Connexion à la base de données
			try
			{
				$bdd = new PDO('mysql:host=localhost; dbname=carhub; charset=utf8_bin', 'root', 'mdp@my5QL');
			}
			catch(Exception $e)
			{
				die('Erreur :'.$e->getMessage());
			}

			// Hachage du mot de passe
			$pass_hache = sha1($_POST['pass']);

			$nbLoginIdentiques=0;
			$reponse = $bdd->prepare('SELECT ID FROM membres WHERE login = :login');
			$reponse->execute(array('login' => $_POST['login']));
			while ($donnees = $reponse->fetch())
			{
				$nbLoginIdentiques+=1;
			}
			$reponse->closeCursor();


			if($nbLoginIdentiques < 1)
 			{
			// Insertion
			$req = $bdd->prepare('INSERT INTO membres(nom, prenom, naissance, login, pass) VALUES(:nom, :prenom, :naissance, :login, :pass)');
			$req->execute(array(
			    'nom' => $_POST['nom'],
			    'prenom' => $_POST['prenom'],
			    'naissance' => $_POST['naissance'],
			    'login' => $_POST['login'],
			    'pass' => $pass_hache));

			$req->closeCursor();

			echo "Votre compte a bien été crée !<br/> Vous pouvez maintenant allez vous authentifier en cliquant sur ce <a href='index.html'>lien<br/></a>";

			if( isset($_POST['upload']) ) // si formulaire soumis
			{
			$content_dir = '../photos/'; // dossier où sera déplacé le fichier

			$tmp_file = $_FILES['fichier']['tmp_name'];

			if( !is_uploaded_file($tmp_file) )
			{
				exit("Le fichier est introuvable.");
			}

			// on vérifie maintenant l'extension
			$type_file = $_FILES['fichier']['type'];

			if( !strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') )
			{
				exit("Le fichier n'est pas une image.");
			}

			// on copie le fichier dans le dossier de destination
			$name_file = $_FILES['fichier']['name'];

			if( !move_uploaded_file($tmp_file, $content_dir . $name_file) )
			{
				exit("Impossible de copier le fichier dans $content_dir");
			}

			echo "Le fichier a bien été uploadé";
		}

//Récupérer propirétés du fichier temporaire, voir les différentes permissions autorisations,
//Regarder dans les logs d'Apache
//Dans dossier bin
			}
			else {
				echo "Ce login est déjà pris !";
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
