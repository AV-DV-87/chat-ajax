<?php
require_once('inc/init.php');
if(isset($_POST['connexion']))
{
    if(!empty($_POST['pseudo'])){ //test de champs pseudo non vide
        $result = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
        $result->execute(array(
            'pseudo'=>$_POST['pseudo']
        ));
        $membre = $result->fetch(PDO::FETCH_ASSOC);
        if($result->rowCount()==0) //si le membre n'existe pas
        {
            //Insertion d'un nouveau membre
            $insert = $pdo->prepare("INSERT INTO membre VALUES (NULL, :pseudo,:civilite,:ville,:date_naiss,:ip, ".time().")");
            $insert->execute(array(
                'pseudo'=>$_POST['pseudo'],
                'civilite'=>$_POST['sexe'],
                'ville'=>$_POST['ville'],
                'date_naiss'=>$_POST['date_naiss'],
                'ip'=>$_SERVER['REMOTE_ADDR']
            ));
            $id_membre = $pdo->lastInsertId(); //on récupère l'id membre une fois qu'il est inscrit en base
        }
        elseif($result->rowCount() > 0 && $membre['ip'] == $_SERVER['REMOTE_ADDR'])
        {
            //le pseudo est connu, il a la même adresse IP
            //Mise à jour de sa dernière date de connexion
            $update = $pdo->prepare("UPDATE membre SET date_connexion = :majdate WHERE id_membre = :id_membre");
            $update->execute(array(
                "id_membre" => $membre['id_membre']
            ));
            $id_membre = $membre['id_membre']; //on récupère l'id du membre qui a été trouvé après mis à jour la date de sa dernière connexion

        }
        else{
            //le pseudo est déjà réservé par quelqu'un
            $msg .= '<div class="erreur">Ce pseudo déjà réservé</div>';
        }
        if(empty($msg)) //si pas d'erreur
        {
            $_SESSION['id_membre'] = $id_membre;
            $_SESSION['pseudo'] = $_POST['pseudo'];
            header('location:index.php'); //redirection vers l'espace de discussion
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AVTchat</title>
    <link rel="stylesheet" href="inc/style.css">
</head>
<body>
    <?= $msg?>
    <form action="" method="post">
        <fieldset>
            <label for="pseudo"> Pseudo<br>
                <input type="text" id="pseudo" name="pseudo">
            </label>
            <p>Laissez les champs suivants vides si vous êtes déjà membre</p>
            
            <label for="ville"> Ville
                <input type="text" id="pseudo" name="ville">
            </label><br>

            <label for="date_naiss"> Date de naissance
                <input type="text" id="date_naiss" name="date_naiss">
            </label><br>

            <label for="sexe">Vous êtes<br>
                <input type="radio" name="sexe" value="m">Un Homme
                <input type="radio" name="sexe" value="f" checked>Une femme
            </label><br>
            <input type="submit" name="connexion" value="Se Connecter au tchat">

        </fieldset>
    </form>
</body>
</html>