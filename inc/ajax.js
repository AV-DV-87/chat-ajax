$(document).ready(function(){
    //iniatlisation du tchat
    convertir_smiley();
    $('#message_tchat').scrollTop($('#message_tchat')[0].scrollHeight); //remonte les messages pour voir le dernier message quelque soit la longueur

    var url ='inc/ajax.php'; //url pour échanger en AJAX
    // var lastid = 0;
    var timer = setInterval(affichage_message,10000); //vérification des messages
    var timer_membre_connecte = setInterval(affichage_membre_connecte,15000); //intervalle rafraichissement membres

    //fn qui rafraichit les messages
    function affichage_message(){
        $.post(url,{'action':'affichage_message','lastid':lastid},function(donnees){
            
            if(donnees.validation == 'ok'){
                $('#message_tchat').append(donnees.resultat);
                lastid = donnees.lastid; //récupération du dernier ID de message pour repartir de celui là à l'ajout du nouveau message
                $('#message_tchat').scrollTop($('#message_tchat')[0].scrollHeight);
                convertir_smiley();
            }

        },'json');
    }

    //affichage liste membre
    function affichage_membre_connecte(){
        $.post(url,{'action':'affichage_membre_connecte'},function(donnees){
            
            if(donnees.validation == 'ok'){ //si insctruction php renvoi ok
                $('#liste_membre_connecte').empty().append(donnees.resultat);

            }
        }
        ,'json');
    }

    //ajout des messages
    $('#formulaire_tchat form').submit(function(){
        clearInterval(timer);
        
        var message = $('#formulaire_tchat form input[name=message]').val(); //je recupere le text du message saisi
        
        $.post(url,{'action':'envoi_message','message':message},function(donnees){
            
            if(donnees.validation == 'ok'){
                affichage_message();
                $('#formulaire_tchat form input[name=message]').val('').focus();
            }
        
        },'json');

        timer = setInterval(affichage_message, 10000);
        return false; //même action qu'un prevent default

    })


    //AFFICHAGE du smiley dans le champs de saisie au clic du smiley
    $(".smiley").on('click', function(event){

        var prevMsg = $("#message").val(); //je stocke le message en cours de saisie dans une variable
        var emotiText = $(event.target).attr('alt'); //on récupère la valeur du alt du smiley cliqué
        $('#message').val(prevMsg + emotiText);//text + smiley
    });
    //CONVERSION Caractère en image smiley
    function convertir_smiley(){
        
        $('#message_tchat p').each(function(){ //pour chaque message

            $(".smiley").each(function(){ //pour chaque smiley
                var symbole = $(this).attr('alt');
                var source  = $(this).attr('src');
                var textRemplace = $('#message_tchat').html().replace(symbole,'<img src="' + source + '">'); 
                //remplacement du contenu du html par html avec la source image
                $('#message_tchat').html(textRemplace);
            })
        })
    };

    //DECONNEXION
    $('#deconnexion').on('click',function(){
        $.post(url,{'action':'deconnexion'},function(donnees){
            if(donnees.validation == 'ok'){
                
                $(location).attr('href','index.php');
            }
        },'json')
    });

}); // Fin du document ready