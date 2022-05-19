<?php 

    //me connecte à la base de donnée 
    //crée notre variable $pdo 
    include("db.php");



    //crée notre propre fonction style var_dump()
    //reçoit la variable à afficher en argument
    function debug($var){
        //la balise <pre> permet de conserver l'indentation
        echo '<pre style="background-color: #000; color: lightgreen; padding: 10px;">';
        print_r($var);
        echo '</pre>';
    }

    //appelle notre fonction de debug pour afficher les données 
    //soumises par le formulaire
    debug($_POST);

    //tout en haut du fichier, on traite le formulaire 
    //seulement s'il est soumis

    //on récupère les données du formulaire dans nos variables
    //à nous

    //si on a des données dans $_POST, 
    //c'est que le form a été soumis
    if(!empty($_POST)){
        //par défaut, on dit que le formulaire est entièrement valide
        //si on trouve ne serait-ce qu'une seule erreur, on 
        //passera cette variable à false
        $formIsValid = true;

        $email = $_POST['email'];
        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $password = $_POST['password'];
        $age = $_POST['age'];

        //tableau qui stocke nos éventuels messages d'erreur
        $errors = [];

        //si le lastname est vide...
        if(empty($lastname) ){
            //on note qu'on a trouvé une erreur ! 
            $formIsValid = false;
            $errors[] = "Veuillez renseigner votre nom de famille !";
        }
        //mb_strlen calcule la longueur d'une chaîne
        elseif(mb_strlen($lastname) <= 1){
            $formIsValid = false;
            $errors[] = "Votre nom de famille est court, très court. Veuillez le rallonger !";
        }
        elseif(mb_strlen($lastname) > 30){
            $formIsValid = false;
            $errors[] = "Votre nom de famille est trop long !";
        }

        //exactement pareil pour le prénom
        //si le firstname est vide...
        if(empty($firstname) ){
            //on note qu'on a trouvé une erreur ! 
            $formIsValid = false;
            $errors[] = "Veuillez renseigner votre prénom !";
        }
        //mb_strlen calcule la longueur d'une chaîne
        elseif(mb_strlen($firstname) <= 1){
            $formIsValid = false;
            $errors[] = "Votre prénom est court, très court. Veuillez le rallonger !";
        }
        elseif(mb_strlen($firstname) > 30){
            $formIsValid = false;
            $errors[] = "Votre prénom est trop long !";
        }

        //validation de l'email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $formIsValid = false;
            $errors[] = "Votre email n'est pas valide !";
        }

        //validation de l'âge
        if($age < 5){
            $formIsValid = false;
            $errors[] = "T'es trop jeune, vas voir ta mère.";
        }
        elseif($age > 130){
            $formIsValid = false;
            $errors[] = "Comon dude";
        }
        //si on n'a pas reçu quelque chose qui ressemble à un nombre
        elseif(!is_numeric($age)){
            $formIsValid = false;
            $errors[] = "Votre âge en chiffres please !";
        }

//si le formulaire est toujours valide... 
if ($formIsValid == true){
    //on écrit tout d'abord notre requête SQL, dans une variable
    $sql = "INSERT INTO users 
            (firstname, lastname, email, password, age, date_created)
            VALUES 
            (:firstname, :lastname, :email, :password, :age, NOW())";

    /*
    injection SQL dans le champs de mot de passe : 
    pass', '44', NOW()); DROP DATABASE kinoa; --
    */

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":firstname" => $firstname,
        ":lastname" => $lastname, 
        ":email" => $email,
        ":password" => $password, 
        ":age" => $age, 
    ]);
}
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>

    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <h1>Créez votre compte !</h1>

    <!-- seuls les formulaires de recherche doivent être en get -->
    <form method="post">
        <div> 
            <label for="lastname">Votre nom</label>
            <input type="text" name="lastname" id="lastname">
        </div>
        <div> 
            <label for="firstname">Votre prénom</label>
            <input type="text" name="firstname" id="firstname">
        </div>
        <div> 
            <label for="email">Votre email</label>
            <input type="email" name="email" id="email">
        </div>
        <div> 
            <label for="password">Votre mot de passe</label>
            <input type="password" name="password" id="password">
        </div>
        <div> 
            <label for="age">Votre âge</label>
            <input type="number" name="age" id="age">
        </div>


        <?php 
        //affiche les éventuelles erreurs de validations
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<div>' . $error . '</div>'    ;
            }
        }   
        ?>

        <button>Envoyer !</button>
    </form>

</body>
</html>