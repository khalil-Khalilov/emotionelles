// REGLAGE DU CARROUSEL
$(document).ready(function(){

    var $carrousel = $('#carrousel'), // on cible le bloc du carrousel
        $img = $('#carrousel img'), // on cible les images contenues dans le carrousel
        indexImg = $img.length, // on définit l'index du dernier élément
        i=0, // on initialise un compteur
        $currentImg = $img.eq(i); // enfin, on cible l'image courante, qui possède l'index i (0 pour l'instant)

$img.css('display','none'); // on cache les images
$currentImg.css('display','block'); // on affiche seulement l'image courante

$carrousel.append('<div class="controls"><span class="prev"><i class="fas fa-chevron-left"></i></span><span class="next"><i class="fas fa-chevron-right"></i></span></div>'); // ajout des boutons précédent et suivant par l'ajout d'une div dans le code HTML

//BOUTON IMAGE SUIVANTE
$('.next').click(function(){ 
    i++; // on incrémente le compteur

    $img.css ('display', 'none'); // on cache les images
    $currentImg=$img.eq(i%indexImg); // on définit la nouvelle image et on utilise % pour créer une boucle infini
    $currentImg.css('display','block'); // puis on l'affiche

});

//BOUTON IMAGE PRÉCÉDENTE
$('.prev').click(function(){ 
    i--; // on décrémente le compteur

    $img.css('display','none');
    $currentImg = $img.eq(i%indexImg); // on définit la nouvelle image
    $currentImg.css('display','block'); // puis on l'affiche

});

//DEFILEMENT AUTOMATIQUE DES IMAGES 
function slideImg(){
    setTimeout(function(){
        if (i < indexImg){ // si le compteur est inférieur au dernier index
            i++; // on l'incrémente
        }
        else { 
            i=0; //sinon,on le remet à 0 
        }
    

    $img.css('display','none');
    $currentImg = $img.eq(i%indexImg);

    $currentImg.css('display','block');

    slideImg(); // on oublie pas de relancer la fonction de la fin 
    
    }, 10000); // on définit l'intervalle à 7000 millisecondes (7s)
}

slideImg();

});