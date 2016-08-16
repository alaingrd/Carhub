<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Mini-chat</title>
	<link rel="stylesheet" type="text/css" href="base.css" media="all" />
	<link rel="stylesheet" type="text/css" href="index.css" media="screen" />
    </head>
    <style>
    form
    {
        text-align:center;
    }
    </style>
    <body>
    

<?php
session_start();
// Connexion à la base de données

try
{
	$bdd = new PDO('mysql:host=localhost; dbname=carhub; charset=utf8', 'root', 'motdepasse');
}
catch(Exception $e)
{
	die('Erreur :'.$e->getMessage());
}



?>




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
			echo "La conversation a été créée avec succès !<br/>";
			echo "Vous pouvez maintenant aller discuter avec ".$login2." en suivant ce <a href='choixInterlocuteurContinuer.php'>lien</a>";
			}
			
			?>
		</div><!-- #contenu -->
	</div><!-- #centre -->


<?php



//On récupère le login de celui qui contacte
$login1 = $_SESSION['login'];



//On récupère le login de celui qui est contacté
$login2 = $_POST["interloc"];


//On crée déjà une entrée dans la table conversations
$req = $bdd->prepare('INSERT INTO conversations(login1, login2) VALUES(:login1, :login2)');
$req->execute(array('login1' => $login1, 'login2' => $login2));
$req->closeCursor();

//On récupère l'ID de la conversation
$req = $bdd->prepare('SELECT ID_conversation FROM conversations WHERE login1 = :login1 AND login2 = :login2');
$req->execute(array('login1' => $login1, 'login2' => $login2));
while ($donnees = $req->fetch())
{
    $IDconv = $donnees['ID_conversation'];
}
$req->closeCursor();


$premierMessage = "La conversation a été créée par ".$login1." !";

//On crée déjà une entrée dans la table messagerie
$req = $bdd->prepare('INSERT INTO messagerie(ID_conversation, login, message) VALUES(:ID_conversation, :login, :message)');
$req->execute(array('ID_conversation' => $IDconv, 'login' => $login1, 'message' => $premierMessage));
$req->closeCursor();
?>
    </body>
</html>
