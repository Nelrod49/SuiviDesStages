<?php
require_once('../Class/PageBase.class.php');
$maConnexion = new Connexion();
$IDC = $maConnexion->IDconnexion;
$idStage = $_GET['idStage'];
$infoCR = $IDC->query('SELECT * FROM COMPTE_RENDU WHERE idS ="'.$idStage.'" ORDER BY dateCR DESC');
foreach ($infoCR as $cr){
	//Compte Rendu téléphonique
	if($cr->type == 1){
		echo '<strong>Voici le compte rendu téléphonique du '.date("d/m/Y", strtotime($cr->dateCR)).' : </strong><br>'.$cr->texteCR.'<br>';
	}
	//Compte rendu visite
	else{
		echo '<strong>Voici le compte rendu de la visite fait le '.date("d/m/Y", strtotime($cr->dateCR)).' :</strong> <br>'.$cr->texteCR.'<br>';
	}
}
?>