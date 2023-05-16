
var fleche1=document.querySelector(".img-svg1");
var fleche2=document.querySelector(".img-svg2");
var slide=document.querySelector(".slide");
var indice_carte_milieu=1;





fleche1.onclick=()=>{
    if(indice_carte_milieu!=1){
    slide.scrollBy(-210,0);
    indice_carte_milieu=indice_carte_milieu-1;
    }
 }

fleche2.onclick=()=>{
   if(indice_carte_milieu!=9){
    slide.scrollBy(210,0);
    indice_carte_milieu=indice_carte_milieu+1;
   }
   
   
}
 