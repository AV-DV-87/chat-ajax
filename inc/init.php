<?php
$pdo = new PDO('mysql:host=localhost;dbname=tchat2','root','',
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

//ouverture de la session utilisateur
session_start();

$msg= '';

//créer trois variables à partir de trois entrées d'un tableau et je les stock
//avec list
function age($naiss){

    list($y, $m, $d) = explode('-',$naiss); 
    if($diff = (date('m') - $m) < 0)
    {
        $y++; //ajout d'une année selon le mois de naissance pour obtenir l'âge réel
    }
    elseif($m==0 && date('d') - $d <0) //cas du mois courant pour affiner l'âge
    {
        $y++;
    }
    return date('Y') - $y;

}