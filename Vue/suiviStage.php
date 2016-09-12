<?php
require_once('../Class/PageBase.class.php');

$pageIndex = new PageBase("Suivi des Stages");
$pageIndex->script = '
<script>
function showEleve(val) {
	if (window.XMLHttpRequest){
			//Code pour IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else {
			//Code pour IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function(){
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
				document.getElementById("selectEleve").innerHTML = xmlhttp.responseText;
			}
		};
		xmlhttp.open("GET","../Controleur/suiviStageClasse.php?classe="+val,true);
		xmlhttp.send();
}
</script>';
//Création du Tableau
$maConnexion = new Connexion();
$pageIndex->contenu ='<select name="classe" onchange="showEleve(this.value)">
					<option value="">Sélectionner une classe </otion>';
$IDC = $maConnexion->IDconnexion;
$classe = $IDC->query('SELECT * FROM CLASSE');
foreach($classe as $c){
	$pageIndex->contenu .='<option value="'.$c->idC.'">'.$c->libC.'</option>';
};
$pageIndex->contenu .='</select>
<div id="selectEleve"></div>';
$pageIndex->afficher();


?>
