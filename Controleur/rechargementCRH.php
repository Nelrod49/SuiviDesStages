<?php
require_once('../Class/PageBase.class.php');
$maConnexion = new Connexion();
$IDC = $maConnexion->IDconnexion;
$idStage = $_GET['idStage'];
$infoCRH = $IDC->query('SELECT * FROM CR_Hebdo WHERE idS ="'.$idStage.'" ORDER BY dateCRH');
$nb = 1;
foreach ($infoCRH as $crh){
	echo 'Le compte rendu de la semaine numéro '.$nb.' a été rendu le ' .date("d/m/Y", strtotime($crh->dateCRH)).'.<br>';
	$nb ++;
}
?>