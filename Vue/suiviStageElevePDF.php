<?php
ob_start();
require_once('../Class/PageBase.class.php');
require_once ('../Class/html2pdf/html2pdf.class.php');
$pageIndex = new PageBase("Suivi de Stages PDF");
$maConnexion = new Connexion();
$IDC = $maConnexion->IDconnexion;
//Id de l'élève sélectionner depuis la page suiviStageClasse
$etudiant = $_GET['eleve'];
$date = date("Y-m-d");?>
<!-- Début du PDF -->
<page>


<!-- 	Tableau avec les information de l'étudiant et de son entreprise de stage -->
	<table  border="1px solid" style="border-collapse: collapse;  ">
		<tr style="text-align: center; background: #ebebeb;">
			<td colspan="2" style="padding-top: 2px;"><h3>Informations générales</h3> </td>
		</tr>
	<?php 
		$infoEtu = $IDC->query('SELECT * FROM ETUDIANT WHERE idE = '.$etudiant.'');
		foreach($infoEtu as $e){
		?>
		<tr>
			<td> <h4>Informations sur l'élève</h4> </td>
			<td> <?php echo $e->nomE; ?> <?php echo $e->prenomE; ?><br /><?php echo $e->telPE; ?><br /><?php echo $e->emailE;?> </td>
		</tr>
	<?php
		$nomEleve = $e->nomE;
		$prenomEleve = $e->prenomE;
	}
	//Récupère les infos sur le stage de l'étudiant (Entreprise et Stage)
	$infoEtu = $IDC->query('SELECT idS, dateDeb, dateFin, sujet, etatConvention, dateVisite,idE, s.idEntreprise, nom, adresse, nomTuteur,emailTuteur, telTuteur FROM STAGE as s 
	INNER JOIN ENTREPRISE as e WHERE s.idEntreprise = e.idEntreprise AND idE = '.$etudiant.'');
	foreach($infoEtu as $ie){
		//Récuperation de l'id de stage de l'étudiant pour la suite 
		$idStage = $ie->idS;
		?>
		<tr>
			<td> <h4>Informations sur l' entreprise de stage</h4> </td>
			<td> <?php echo $ie->nom; ?><br /><?php echo $ie->adresse; ?><br /><?php echo $ie->nomTuteur; ?><br /><?php echo $ie->emailTuteur; ?><br /><?php echo $ie->telTuteur; ?></td>
		</tr>
	<?php
		?>
		<tr>
			<td> <h4>Sujet de stage</h4> </td>
			<td> <?php echo $ie->sujet; ?> </td>
		</tr>
		<tr>
			<td> <h4>État de la convention</h4> </td>
			<td>
	<?php
		switch ($ie->etatConvention){
			case '1':
				?> Pas de convention signée<br /> <?php
			break;
			case '2':
				?> La convention peut être signée par l' étudiant et l' entreprise <br /> (l' étudiant a eu un entretien avec réponse favorable de l' entreprise)<br /> <?php
			break;
			case '3':
				?> La convention peut être signée par l' étudiant, l' enseignant / lycée et l' entreprise <br />(le lycée est le dernier signataire de la convention)<br /> <?php
			break;
		}
		if($ie->dateVisite == '0000-00-00')
		{
			?>Pas de date de visite de prévu actuellement
			</td>
		</tr><?php
		}
		else
		{			
			?>La date de visite est prévue pour le <?php echo date('d/m/Y', strtotime($ie->dateVisite));?>
			</td>
		</tr><?php		
		}
	}
	?>
	
	</table>
	<br />



<!-- 	Tableau des comptes rendus hebdomadaire -->
<?php
	$infoCRH = $IDC->query('SELECT * FROM CR_Hebdo WHERE idS ="'.$idStage.'" ORDER BY dateCRH');
	if($infoCRH->rowCount() !=0){
	?>
	<table border="1px solid" style="border-collapse: collapse;"> 
		<tr style="text-align: center; background: #ebebeb;">
			<td colspan="2"> <h3>Compte Rendu Hebdomadaire Étudiant</h3>
			</td> 
		</tr>
		<tr>
			<td style="text-align: left;">	
	<?php
	
	$nb = 1;
	foreach ($infoCRH as $crh){
		?>	
				Le compte rendu de la semaine numéro <?php echo $nb ;?>a été rendu le<strong>  <?php echo date('d/m/Y', strtotime($crh->dateCRH))?></strong><br /><br />
			
		<?php
		$nb ++;
	}
	?>
			</td>	
		</tr>
	</table>
	<br /><?php } ?>






<!-- 	Tableau des comptes rendus du professeur -->
<?php 
	$infoCR = $IDC->query('SELECT * FROM COMPTE_RENDU WHERE idS ="'.$idStage.'" ORDER BY dateCR DESC');
	if($infoCR->rowCount() !=0){
	?>
	<table border="1px solid" style="border-collapse: collapse;"> 
		<tr style="text-align: center; background: #ebebeb;">
			<td colspan="2"> <h3>Compte Rendu Professeur</h3>
			</td>
		</tr>
		<tr style="text-align: left;">
			<td>
	<?php	
	foreach ($infoCR as $cr){
		switch ($cr->type ){
			//Compte rendu : Téléphonique
			case '1':
				?><strong>Voici le compte rendu téléphonique du  <?php echo date('d/m/Y', strtotime($cr->dateCR)); ?> :</strong> <br /><?php echo $cr->texteCR; ?><br /><br /><?php
			break;
			//Compte rendu : Visite
			case '2':
				?><strong>Voici le compte rendu de la visite fait le <?php echo date('d/m/Y', strtotime($cr->dateCR)); ?> :</strong><br /><?php echo $cr->texteCR; ?><br /><br /><?php
			break;
			//Compte rendu : Mail
			case '3':
				?><strong>Voici le compte rendu fait par mail du <?php echo date('d/m/Y', strtotime($cr->dateCR)); ?> :</strong> <br /><?php echo $cr->texteCR; ?><br /><br /><?php
			break;
		}
	}
	?>
			</td>
		</tr>
	</table><?php } ?>
</page>
<?php
$content = ob_get_clean();
$pdf = new HTML2PDF('P','A4','fr','true','UTF-8',array(5, 5, 5, 5));
$pdf->writeHTML($content);
$pdf->Output($nomEleve.$prenomEleve.'.pdf' );
ob_end_clean(); ?>
