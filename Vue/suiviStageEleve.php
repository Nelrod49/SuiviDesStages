<?php
require_once('../Class/PageBase.class.php');
$pageIndex = new PageBase("Suivi de Stages");
$maConnexion = new Connexion();
$IDC = $maConnexion->IDconnexion;
//Id de l'élève sélectionner depuis la page suiviStageClasse
$etudiant = $_GET['eleve'];
$date = date("Y-m-d");



//Tableau avec les infos de l'étudiant et de son entreprise de stage
$infoEtu = $IDC->query('SELECT * FROM ETUDIANT WHERE idE = '.$etudiant.'');
$pageIndex->contenu = '<table><td colspan="2"><center><h3>Informations générales</h3></center></td>';
foreach($infoEtu as $e){
	$pageIndex->contenu .= '<tr><td><h4>Info sur l\'élève</h4></td><td> '.$e->nomE.' ' .$e->prenomE.'<br />'.$e->telPE.'<br />'.$e->emailE.'</td></tr>';
	$nomE = $e->nomE;
	$prenomE = $e->prenomE;

}
//Récupère les infos sur le stage de l'étudiant (Entreprise et Stage)
$infoEtu = $IDC->query('SELECT idS, dateDeb, dateFin, sujet, etatConvention, dateVisite,idE, s.idEntreprise, nom, adresse, nomTuteur,emailTuteur, telTuteur FROM STAGE as s INNER JOIN ENTREPRISE as e WHERE s.idEntreprise = e.idEntreprise AND idE = '.$etudiant.'');
foreach($infoEtu as $ie){
	//Récuperation de l'id de stage de l'étudiant pour la suite 
	$idStage = $ie->idS;
	$pageIndex->contenu .= '<tr><td><h4>Information sur l\'entreprise de stage</h4></td><td>'.$ie->nom.'<br />'.$ie->adresse.'<br />'.$ie->nomTuteur.'<br />'.$ie->emailTuteur.'<br />'.$ie->telTuteur.'</td></tr>';
	$pageIndex->contenu .= '<tr><td><h4>Sujet de stage</h4></td><td>'.$ie->sujet.'</td>';
	$pageIndex->contenu .= '<tr><td><h4>État de la convention</h4></td><td id="rechargementV">';
	switch ($ie->etatConvention){
		case '1':
			$pageIndex->contenu .= 'Pas de convention signée<br />';
		break;
		case '2':
			$pageIndex->contenu .= 'La convention peut être signée par l\'étudiant et l\'entreprise (l\'étudiant a eu un entretien avec réponse favorable de l\'entreprise)<br />';
		break;
		case '3':
			$pageIndex->contenu .= 'La convention peut être signée par l\'étudiant, l\'enseignant/lycée et l\'entreprise (le lycée est le dernier signataire de la convention)<br />';
		break;
	}
	if($ie->dateVisite == '0000-00-00')
	{
		$pageIndex->contenu .= 'Pas de date de visite de prévu actuellement
		<form name="dateVisite">
			<input type="date" id="dateV" name="dateV">
			<input type="button" value="Ajouter" onClick="DateVisite()">
		</form>
		</td></tr> </table>';
	}
	else
	{
		
		$pageIndex->contenu .= 'La date de visite est prévue pour le '.date("d/m/Y", strtotime($ie->dateVisite));
		
	}
}
$pageIndex->contenu .= '<form name="dateVisite">
			<input type="date" id="dateV" name="dateV">
			<input type="button" value="Ajouter" onClick="DateVisite()">
		</form>
		</td></tr> </table>';


//Scripts placer ici car l'on reçoit l'id du stage seulement avant
$pageIndex->script = '

<!-- Importe la bibliothèque jquery pour le rechargement automatique des données $().load(); -->
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript">
</script>

<!-- Permet la modification de la date de visite -->
<script type="text/javascript">	
	function DateVisite() {
		var frm = document.getElementsByName("dateVisite")[0];
	    var dateVi =  document.getElementById("dateV").value;
	    if(!Date.parse(dateVi) == true)
	    {
			alert("Veuillez entrer une date");
	    }
		else if (confirm("Est-vous sûre de modifier la date de visite ?")) 
       {	
    		xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET","../Controleur/modifeDateVisite.php?idStage='.$idStage.'&date="+dateVi,true);
			xmlhttp.send();	
			frm.reset(); 
			$("#rechargementV").load("../Controleur/rechargementDateVisite.php?idEtudiant='.$etudiant.'");
		}
	}
</script>

<!-- Permet l\affichage d\'une fenêtre pour l\'ajout de compte hebdomadaire fait par un élève -->
<script type="text/javascript">
   function ajoutCRH() {
		var frm = document.getElementsByName("AjoutCRH")[0];
	    var dateCRH =  document.getElementById("dateCRH").value;
	    if(!Date.parse(dateCRH) == true)
	    {
			alert("Veuillez entrer une date");
	    }
		else if (confirm("Est-vous sûre d\'ajouter un compte rendu hebdomadaire ?")) 
       {	
    		xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET","../Controleur/ajoutCRH.php?idStage='.$idStage.'&date="+dateCRH,true);
			xmlhttp.send();	
			frm.reset(); 
			$("#rechargementCRH").load("../Controleur/rechargementCRH.php?idStage='.$idStage.'");
		}
   }
</script>


<!-- Permet l\affichage d\'une fenêtre pour l\'ajout de compte rendu fait par le professeur -->
<script type="text/javascript">	
	function ajoutCR() {
		var typecr = document.getElementsByName("typeCR");
		var frm = document.getElementsByName("AjoutCR")[0];
		var texte =  document.getElementById("CRtext").value;
		var dateCR =  document.getElementById("dateCR").value;
		var type;
		for(var i = 0; i < typecr.length; i++)
			{
		    	if(typecr[i].checked){
				type = typecr[i].value;
		    }
		}
		if(texte == "")
		{
			alert("Veuillez écrire un compte rendu");
		}
		else if (!Date.parse(dateCR) == true)
	    {
			alert("Veuillez entrer une date");
	    }
		else if (confirm("Est-vous sûre d\'ajouter un compte rendu hebdomadaire ?")) 
		{
			xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET","../Controleur/ajoutCR.php?idStage='.$idStage.'&CRtexte="+texte+"&typeCR="+type+"&date="+dateCR,true);
			xmlhttp.send();
			frm.reset(); 
			$("#rechargementCR").load("../Controleur/rechargementCR.php?idStage='.$idStage.'");
		}
   }
</script>

<!-- Type date pour tous les autres navigateurs que Chrome -->
<!-- cdn for modernizr, if you haven\'t included it already -->
<script src="http://cdn.jsdelivr.net/webshim/1.12.4/extras/modernizr-custom.js"></script>
<!-- polyfiller file to detect and load polyfills -->
<script src="http://cdn.jsdelivr.net/webshim/1.12.4/polyfiller.js"></script>
<script>
  webshims.setOptions("waitReady", false);
  webshims.setOptions("forms-ext", {types: "date"});
  webshims.polyfill("forms forms-ext");
</script>';



//Affichage des comptes rendus hebdomadaire
$pageIndex->contenu .= '<table> <td colspan="2"> <center> <h3>Compte Rendu Hebdomadaire Étudiant</h3> </center> </td> <tr><td id="rechargementCRH" colspan="2"> ';
$infoCRH = $IDC->query('SELECT * FROM CR_Hebdo WHERE idS ="'.$idStage.'" ORDER BY dateCRH');
$nb = 1;
foreach ($infoCRH as $crh){
	$pageIndex->contenu .= 'Le compte rendu de la semaine numéro '.$nb.' a été rendu le ' .date("d/m/Y", strtotime($crh->dateCRH)).'.<br />';
	$nb ++;
}

//Ajout d'un compte rendu hebdomadaire
$pageIndex->contenu .= '</td></tr><tr><td><h4>Ajouter un compte rendu hebdomadaire</h4></td><td>
<form name="AjoutCRH">
	<input type="date" id="dateCRH" max="'.$date.'" name="dateCRH">
	<input type="button" value="Ajouter" onClick="ajoutCRH()">
</form>
<div id=confirmation"></div>
</td></tr></table>';




//Affichage Compte rendu du professeur
$pageIndex->contenu .= '<table> <td colspan="2"> <center> <h3>Compte Rendu Professeur</h3> </center> </td> <tr><td id="rechargementCR" colspan="2"> ';

$infoCR = $IDC->query('SELECT * FROM COMPTE_RENDU WHERE idS ="'.$idStage.'" ORDER BY dateCR DESC');
foreach ($infoCR as $cr){
	switch ($cr->type ){
		//Compte rendu : Téléphonique
		case '1':
			$pageIndex->contenu .= '<strong>Voici le compte rendu téléphonique du '.date("d/m/Y", strtotime($cr->dateCR)).' : </strong><br />'.$cr->texteCR.'<br />';
		break;
		//Compte rendu : Visite
		case '2':
			$pageIndex->contenu .= '<strong>Voici le compte rendu de la visite fait le '.date("d/m/Y", strtotime($cr->dateCR)).' :</strong> <br />'.$cr->texteCR.'<br />';
		break;
		//Compte rendu : Mail
		case '3':
			$pageIndex->contenu .= '<strong>Voici le compte rendu fait par mail du '.date("d/m/Y", strtotime($cr->dateCR)).' :</strong> <br />'.$cr->texteCR.'<br />';
		break;
	}
}
//Formaire pour l\'ajout d\'un compte rendu.
$pageIndex->contenu .= '</td> </tr><tr><td><center> <h4>Ajouter un compte rendu d\'entretien</h4></center>
<form  name="AjoutCR">
	<textarea maxlength="1000" id="CRtext" name="CRtext" placeholder="Entrer ici votre compte rendu..." required></textarea>
	<center>
		<input type="date" id="dateCR" max="'.$date.'" name="dateCR">
		<h5>Type de compte rendu :</h5>
		<label for="1">Téléphonique	<input type="radio" name="typeCR" value="1"  checked/> </label>
		<label for="2">Visite	<input type="radio" name="typeCR" value="2"/></label> 
		<label for="2">Mail	<input type="radio" name="typeCR" value="3"/></label>
		 <br /><br />	
		<input type="button" value="Envoyer"  onclick="ajoutCR()" />
		<input type="reset" value="Annuler"/>
	</center>
</form>
</td></tr></table>';




//Bouton de téléchargement du PDF
$pageIndex->contenu .= '
<center>
	<a href="../Vue/suiviStageElevePDF.php?eleve='.$etudiant.'">Télécharger le PDF</a>
</ center>';



$pageIndex->afficher();
?>
