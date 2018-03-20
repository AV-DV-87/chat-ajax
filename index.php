<?php

// echo "mon IP est".gethostbyname($_SERVER['SERVER_NAME']);
//renvoi l'adresse ip du client
require_once('inc/init.php');

if(!isset($_SESSION['pseudo'])) //si pas de pseudo en session retour à la case inscription
{
    header('location:connexion.php');
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="inc/style.css" rel="stylesheet">
        <title>AVTchat</title>
    </head>
    <body>
    <div id="conteneur">
        <div id="message_tchat">
            <h2>Connecté en tant que <?=$_SESSION['pseudo']?></h2>
            <?php
                $result = $pdo->query("SELECT d.id_dialogue,m.pseudo,m.civilite, d.message, date_format(d.date,'%d/%m/%Y - %H:%i:%s') as datefr
                                        FROM dialogue d, membre m
                                        WHERE m.id_membre=d.id_membre ORDER BY date");
                while($dialogue = $result->fetch(PDO::FETCH_ASSOC))
                {
                    if($dialogue['civilite'] == 'm') {$color="bleu";}
                    if($dialogue['civilite'] == 'f') {$color="rose";}
                    ?>
                    <p class="<?=$color?>"><?=$dialogue['datefr']?> <strong><?=$dialogue['pseudo']?></strong>&#9658
                    <?=$dialogue['message']?></p>
                    <?php
                }
            ?>
        </div>
        <div id="liste_membre_connecte">
            <?php
            // récupération des membres ayant une activité dans la dernière heure
            $result = $pdo->query("SELECT * FROM membre WHERE date_connexion > ".(time() - 3600). " ORDER BY pseudo"); //attention concatenation faire un espace après
            while($membre = $result->fetch(PDO::FETCH_ASSOC))
            {
                if($membre['civilite'] == 'm') {$color="bleu"; $civ="homme";}
                if($membre['civilite'] == 'f') {$color="rose"; $civ="femme";}
                ?>
                <p class="<?= $color ?>" title="<?=$civ.','.$membre['ville'].','.age($membre['date_de_naissance']).'ans'?>"><?=$membre['pseudo']?></p>
                <?php
            }
            ?>
        </div>
        <div class="clear">
        
        </div>
        <div id="smiley">
            <img class="smiley" src="smil/smiley1.gif" alt=":)">
            <img class="smiley" src="smil/smiley2.gif" alt="/{">
            <img class="smiley" src="smil/smiley3.gif" alt="^^">
            <img class="smiley" src="smil/smiley4.gif" alt=":(">
        </div>
        <div id="formulaire_tchat">
            <form action="#" method="post">
                <input type="text" id='message' name="message" maxlength="255" class="textarea">
                <input type="submit" name="envoi" value="envoi" class="submit">
                <input type="submit" name="deconnexion" value="deconnexion" id="deconnexion">
            </form>
        </div>
    </div>




    <script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
    <script src="inc/ajax.js"></script>
    <script> 
        <?php
        $result = $pdo->query("SELECT id_dialogue FROM dialogue ORDER BY id_dialogue DESC LIMIT 0,1");
        $info = $result->fetch(PDO::FETCH_ASSOC);
        ?>
        var lastid = <?=$info['id_dialogue'] ?? 0 ?>
        </script>
    </body>

</html>