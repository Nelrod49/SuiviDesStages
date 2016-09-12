<?php
require_once('../Class/PageBase.class.php');
$maConnexion = new Connexion();
$IDC = $maConnexion->IDconnexion;
$etudiant = $_GET['idEtudiant'];
$infoEtu = $IDC->query('SELECT idS, dateDeb, dateFin, sujet, etatConvention, dateVisite,idE, s.idEntreprise, nom, adresse, nomTuteur,emailTuteur, telTuteur FROM STAGE as s INNER JOIN ENTREPRISE as e WHERE s.idEntreprise = e.idEntreprise AND idE = '.$etudiant.'');
foreach($infoEtu as $ie){
	switch ($ie->etatConvention){
		case '1':
			echo 'Pas de convention signée<br>';
		break;
		case '2':
			echo 'La convention peut être signée par l\'étudiant et l\'entreprise (l\'étudiant a eu un entretien avec réponse favorable de l\'entreprise)<br>';
		break;
		case '3':
			echo 'La convention peut être signée par l\'étudiant, l\'enseignant/lycée et l\'entreprise (le lycée est le dernier signataire de la convention)<br>';
		break;
	}
	if($ie->dateVisite == '0000-00-00')
	{
		echo 'Pas de date de visite de prévu actuellement';
	}
	else
	{
		echo 'La date de visite est prévue pour le '.date("d/m/Y", strtotime($ie->dateVisite));
	}
}
echo '<form name="dateVisite">
			<input type="date" id="dateV" name="dateV">
			<input type="button" value="Ajouter" onClick="DateVisite()">
		</form>
		</td></tr> </table>';
?>