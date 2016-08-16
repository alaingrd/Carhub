<?php
session_start();

// Connexion à la base de données
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=carhub;charset=utf8', 'root', 'motdepasse');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}


// Suppression des variables de session et de la session
$_SESSION = array();
session_destroy();

// Redirection du visiteur vers la page du minichat
header('Location: index.html');
?>
