<?php 

    function show404()
    {
        //on pète une 404 à l'internaute
        //on envoie un code d'erreur 404
        header("HTTP/1.0 404 Not Found");
        //on affiche notre fichier contenant le code HTML
        include("404.php");
        //on arrête ici l'exécution du script ! 
        //on ne veut pas que le reste du code soit lu
        die();
    }

    //si je n'ai pas d'id dans l'URL...
    if (empty($_GET['id'])){
        show404();
    }

    //récupère l'id du film présent dans l'url 
    //$_GET contient les paramètres d'URL
    //'id' ici est le nom du paramètre dans l'URL
    $movieId = $_GET['id'];

    //juste pour débugger
    //echo $movieId;

    //se connecter à la bdd
    include("db.php");

    //écrire ma requête SQL pour récupérer ce film uniquement 
    $sql = "SELECT * 
            FROM movies 
            WHERE id = :movieId";

    //envoyer la requête à Mysql
    $stmt = $pdo->prepare($sql);

    //exécuter la requête 
    $stmt->execute([":movieId" => $movieId]);

    //récupérer le résultat avec ->fetch()
    $movie = $stmt->fetch();

    //si ce film n'existe pas dans la bdd... 
    //c'est une erreur 404 ! 
    if(empty($movie)){
        show404();
    }

    //faire un echo du title du film
    //echo $movie['title'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kinoa</title>
    <link href="https://fonts.googleapis.com/css?family=Gayathri&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <h1>Kinoa!</h1>

    <main class="movie-detail">
        <div class="left-col">
            <img src="img/posters/<?php echo $movie['image']; ?>" alt="<?php echo $movie['title']; ?>">

<a href="delete-movie.php?id=<?= $movie['id'] ?>">Delete this movie!</a>

        </div>
        <div class="col">
            <h2><?php echo $movie['title']; ?> (<?php echo $movie['year'] ?>)</h2>

            <div>
        <?php 
            //on sépare chaque catégorie... et on les récupère dans un tableau
            $genres = explode(" / ", $movie['genres']); 
            //on boucle sur le tableau pour afficher chaque genre dans sa propre div
    foreach($genres as $genre){
        echo '<a href="genres.php?g='.$genre.'" class="badge">' . $genre . '</a>';
    }
        ?>
            </div>
            <p><?php echo $movie['plot']; ?></p>
            <div>
                <h3>Rating</h3>
            <?php
            //arrondit mon rating au plus proche entier
            //ceil() ou floor() aussi...
            $roundedRating = round($movie['rating']);
            //répète une chaîne x nombre de fois... 
            echo str_repeat('⭐', $roundedRating);

            //version avec une boucle
            //for($i=0; $i < $movie['rating']; $i++){
            //    echo '⭐';
            //}
            
            ?>
            </div>
            <div>
                <h3>Directed by</h3> 
                <?php echo $movie['directors']; ?>
            </div>
            <div>
                <h3>Stars</h3> 
                <?php echo $movie['actors']; ?>
            </div>
            <div>
                <h3>Durée</h3>
                <?php 
                    //on arrondit vers le bas ! 
                    $hours = floor($movie['runtime'] / 60);
                    //modulo 60, ça donne le nombre de minutes restantes 
                    $minutes = $movie['runtime'] % 60;
                    echo "$hours heures $minutes minutes";
                ?>
            </div>
        </div>
    </main>
    
</body>
</html>