<?php 

// connexion à la bdd
include("db.php");

//va chercher dans l'URL l'id du film à effacer 
$movieId = $_GET['id'];

//requête sql 
$sql = "DELETE FROM movies 
        WHERE id = :movieId";

//envoie la requête à mysql
$stmt = $pdo->prepare($sql);
 
//exécute la requête 
$stmt->execute([':movieId' => $movieId]);

//redirige le user vers l'accueil
header("Location: index.php");