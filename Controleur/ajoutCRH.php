<?php
require_once('../Class/PageBase.class.php');
$maConnexion = new Connexion();
$IDC = $maConnexion->IDconnexion;
$idStage = $_GET['idStage'];
$date = $_GET['date'];
$insertion =  $IDC->query('INSERT INTO CR_Hebdo (idCRH,dateCRH,idS) VALUES (NULL,"'.$date.'",'.$idStage.')');
?>

