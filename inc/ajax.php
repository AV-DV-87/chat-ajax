<?php
require_once('init.php');

$tab = array();
extract($_POST); //ouvre la superglobale post et créer une variable par index

//trois condidions pour trois fonctions JS correspondantes
if($action == 'affichage_message'){

    $lastid = (integer)($lastid);
    $result = $pdo->prepare("SELECT d.id_dialogue,m.pseudo,m.civilite,d.message,date_format(d.date, '%d/%m/%Y - %H:%i:%s') as datefr 
                            FROM dialogue d, membre m
                            WHERE d.id_dialogue > :lastid AND d.id_membre = m.id_membre
                            ORDER BY d.date ASC"); //les nouveaux messages à partir du dernier id de message afffiché
    
    if($result->execute(array(
        'lastid' => $lastid
    ))){
        $tab['validation'] = 'ok';
    
        $tab['resultat'] = '';
        $tab['lastid'] = $lastid;
        while($message = $result->fetch(PDO::FETCH_ASSOC))
        {
            if($message['civilite'] == 'm') {$color = "bleu";}
            if($message['civilite'] == 'f') {$color = 'rose';}

            $tab['resultat'] .='<p class="'.$color.'"> '.$message['datefr'].' <strong> '.$message['pseudo'].
            '</strong> &#9658; ' . $message['message'] . '</p>';
            $tab['lastid'] = $message['id_dialogue'];
        }
    } //alimentation du fil de message seulement si on a un retour de la requête donc des nouveaux messages
}// fin de affichage_message

if($action == 'affichage_membre_connecte'){
    $result = $pdo->query("SELECT * FROM membre WHERE date_connexion > ".(time() - 3600). " ORDER BY pseudo"); //attention concatenation faire un espace après
        $tab['resultat'] = '<h2>Membres connectés</h2>';
        if($result->rowCount() > 0)
        {
            $tab['validation'] = 'ok'; //condition à remplir pour effectuer la MAJ
        }
        //liste des membres en activité dans la dernière heure
        while($membre = $result->fetch(PDO::FETCH_ASSOC))
            {
                if($membre['civilite'] == 'm') {$color="bleu"; $civ="homme";}
                if($membre['civilite'] == 'f') {$color="rose"; $civ="femme";}
                
                $tab['resultat'] .= '<p class="'. $color .'" title="'.$civ.','.$membre['ville'].',' . age($membre['date_de_naissance']) . ' ans">
                '. ucfirst($membre['pseudo']) . '</p>';
                
            }
}

if($action == 'envoi_message'){

    $message = htmlspecialchars($message, ENT_QUOTES); //empeche injection de code mais garde les guillemets

    if(!empty($message)) //si le message n'est pas vide
    {
        
        //insertion d'un message
        $result = $pdo->prepare("INSERT INTO dialogue (id_membre, message, date) 
                                    VALUES (:id_membre, :message, now())");
        if($result->execute(array(
            'id_membre' => $_SESSION['id_membre'], //le membre connécté
            'message'   => $message
        ))){
        $tab['validation'] = 'ok';
        }

        //actualisation de la dernier date d'activité du membre qui vient d'écrire
        $result = $pdo->prepare("UPDATE membre SET date_connexion = :date_connexion WHERE id_membre = :id_membre");
        $result->execute(array(
                'date_connexion' => time(),
                'id_membre' => $_SESSION['id_membre']
        ));
    }
}

if($action == 'deconnexion'){
    session_destroy();
    $tab['validation'] = 'ok';
}

echo json_encode($tab);

