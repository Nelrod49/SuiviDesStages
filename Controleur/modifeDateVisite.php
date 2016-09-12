<?php
require_once('../Class/PageBase.class.php');
$maConnexion = new Connexion();
$IDC = $maConnexion->IDconnexion;
$idStage = $_GET['idStage'];
$date = $_GET['date'];
$insertion =  $IDC->query('UPDATE STAGE SET dateVisite = "'.$date.'" WHERE idS = "'.$idStage.'"');
?>

