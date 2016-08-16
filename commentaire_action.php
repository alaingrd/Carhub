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
    
    <form action="commentaire_action_post.php" method="post">
        <p>
        <label for="commentaire">Commentaire à envoyer</label> :  <input type="text" name="commentaire" id="commentaire" /><br />
        <input type="submit" value="Envoyer" />
	</p>
    </form>

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

//On récupère le login de celui en ligne (celui qui commente)
$req = $bdd->prepare('SELECT login FROM membres WHERE ID = :id');

$req->execute(array('id' => $_SESSION['ID']));
while ($donnees = $req->fetch())
{
    $login1 = $donnees['login'];
}

$req->closeCursor();


//On récupère le login de celui qui est commenté
$login2 = $_POST["commente"];
$_SESSION['commente'] = $login2;
$login2 = $_SESSION['commente'];





//On récupère les commentaires précédents
$reponse = $bdd->prepare('SELECT login_commentant, commentaire FROM commentaires WHERE login_commente = :login_commente ORDER BY ID_commentaire DESC');
$reponse->execute(array('login_commente' => $login2));

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

	<div id="pied">
		<p><strong><a href="deconnexion.php">[SE DÉCONNECTER]</a></strong></p>
		<p id="copyright">
			<strong>voiturehub.fr© Tous droits réservés</strong> UV LO07
		</p>
	</div><!-- #pied -->
    </body>
</html>
