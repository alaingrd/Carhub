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


	//On récupère le login de celui qui est contacté avec une variable de session, puisque le poste n'est plus valble
	$login2 = $_SESSION["interloc"];

	//On récupère le login de celui qui envoie
	$login1 = $_SESSION['login'];

	//On récupère l'ID de la conversation
	$req = $bdd->prepare('SELECT ID_conversation FROM conversations WHERE login1 = :login1 OR login1 = :login2 AND login2 = :login2 OR login2 = :login1');
	$req->execute(array('login1' => $login1, 'login2' => $login2));
	while ($donnees = $req->fetch())
	{
	    $IDconv = $donnees['ID_conversation'];
	}
	$req->closeCursor();



	// Insertion du message à l'aide d'une requête préparée
	echo $_POST['message'];

	$req = $bdd->prepare('INSERT INTO messagerie (login, message, ID_conversation) VALUES(?, ?, ?)');
	$req->execute(array($login1, $_POST['message'], $IDconv));

	 echo "<form method='POST' action = 'messagerie_action.php'>";

	echo '<input type="hidden" name="interloc" value="' . htmlspecialchars($login2) . '" />'."\n";

	echo "<input type='submit' value='[Retourner à la discussion]'>";

	echo "</form>";


	// Redirection du visiteur vers la page du minichat
	//header('Location: messagerie_action.php');
	?>
</body>
</html>
