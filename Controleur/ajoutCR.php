<?php
require_once('../Class/PageBase.class.php');
$maConnexion = new Connexion();
$IDC = $maConnexion->IDconnexion;
$texte = $_GET['CRtexte'];
$type = $_GET['typeCR'];
$idStage = $_GET['idStage'];
$date = $_GET['date'];
$insertion =  $IDC->query('INSERT INTO COMPTE_RENDU (idCR,texteCR,dateCR,type,idS) VALUES (NULL,"'.$texte.'","'.$date.'",'.$type.','.$idStage.')');
?>

