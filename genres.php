<?php 
    //connexion à la bdd 
    include("db.php");

    //récupére le genre choisi qui est présent dans l'URL
    //strip_tags enlève les éventuelles balises HTML
    //pour se protéger des attaques XSS
    $genre = strip_tags($_GET['g']);

    //ma requête sql pour récupérer les films d'un seul genre
    //pour l'instant, on ne fait que stocker la requête dans une chaîne
    $sql = "SELECT * 
            FROM movies 
            WHERE genres LIKE :genre
            ORDER BY RAND()";

    //envoie ma requête SQL au serveur MySQL
    $stmt = $pdo->prepare($sql);

    //demande à MySQL d'exécuter ma requête 
    //(les données sont toujours là-bas !)
    $stmt->execute([":genre" => "%$genre%"]);

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

    <link href="https://fonts.googleapis.com/css?family=Gayathri&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <h1>Kinoa!</h1>

    <?php 
    // La balise <?= permet d'ouvrir PHP et faire un echo 
    // 2 en 1 raccourci ! 
    // short echo tag
    ?>
    <h2><?= htmlentities($genre) ?> movies</h2>

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
