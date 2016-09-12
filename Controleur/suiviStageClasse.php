<?php
require_once('../Class/PageBase.class.php');
$maConnexion = new Connexion();
$IDC = $maConnexion->IDconnexion;
$classe = $_GET['classe'];
//Éviter les erreurs PDO car la variable classe est vide
if($classe == ""){
}
else
{
$etudiant = $IDC->query('SELECT * FROM ETUDIANT WHERE idC = '.$classe.' ORDER BY nomE');
?>
<ul>
<?php
foreach($etudiant as $e){
	echo '<li><a href=suiviStageEleve.php?eleve='.$e->idE.'>'.$e->nomE.' '.$e->prenomE.'</a></li>';
}
?>
</ul><?php	
}


