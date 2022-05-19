<?php 
    //connexion à la bdd 
    include("db.php");

    //ma requête sql pour récupérer 30 films au hasard
    //pour l'instant, on ne fait que stocker la requête dans une chaîne
    $sql = "SELECT * 
            FROM movies
            ORDER BY RAND() 
            LIMIT 30";

    //envoie ma requête SQL au serveur MySQL
    $stmt = $pdo->prepare($sql);

    //demande à MySQL d'exécuter ma requête 
    //(les données sont toujours là-bas !)
    $stmt->execute();

    //récupère les films depuis le serveur MySQL
    // ->fetch() pour récupérer une seule ligne ! 
    $movies = $stmt->fetchAll();

    //pour débugger, pour voir si j'ai bien récupérer des films
    //var_dump($movies);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kinoa</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <h1>Bienvenu sur Kinoa!</h1>
    <section class="movies-list">

<!-- affiche les 30 films -->
<?php 



foreach($movies as $movie){
    echo '<a href="detail.php?id='.$movie['id'].'" title="'.$movie['title'].'">';
        echo '<img src="img/posters/' . $movie['image'] . '" alt="'.$movie['title'].'">';
    echo '</a>';
}

?>

    </section>
</body>
</html>