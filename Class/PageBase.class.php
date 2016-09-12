<?php
require_once ('../Class/Connexion.class.php');
Class PageBase {

	/* Propriétés privé de la classe */
	private $style=array('main','tableStyle','perso');//Mettre le CSS içi
	private $titre;
	private $entete;
	private $contenu;
	private $piedDePage;
	private $script;


	/* Constructeur */
	public function __construct($t){
		$this->titre = $t;
		$this->entete = '<a  href="../Vue/index.php"> Accueil </a>';
		$this->menu = '<nav id="nav">
								<ul class="container">
									<li> <a href="#">Ajout de Stage</a> </li>
									<li><a href="../Vue/suiviStage.php">Suivi de Stage</a> </li>
								</ul>
						</nav>
		';
		$this->piedDePage = 'Copyright <a href="../Vue/Style/"> Lycée Chevrollier</a>';

	} 

	/* Accesseurs */
	public function __set($propriete,$valeur){
		switch($propriete){
			case 'contenu':{
				$this->contenu = $valeur;
				break;
			}
			case 'titre':{
				$this->titre = $valeur;
				break;
			}
			case 'menu':{
				$this->menu = $valeur;
				break;
			}		
			case 'style':{
				$this->style[count($this->style)+1] = $valeur;
				break;
			}			
			case 'script':{
				$this->script = $valeur;
		break;

			}
			}
		}
	public function __get($propriete){
		switch ($propriete){
			case 'contenu':{
				return $this->contenu ;
		break;
			}
			case 'script':{
				return $this->script;
			break;
			}
		}
	}
	/*Chargement des feuilles de styles*/
	private function charge_style(){
		
		foreach ($this->style as $s){
			echo "<link rel='stylesheet' type='text/css' href='../Vue/Style/".$s.".css'/>";
			echo("\n");
		}
	}
	/*Insertion de l'en tête*/
	private function affiche_entete(){
		echo'<h1>'.$this->entete.'</h1>';
	}
	/*Insertion du contenu*/
	private function affiche_contenu(){
		echo $this->contenu;
	}
	/*Insertion de script*/
	private function affiche_script(){
		echo $this->script;
	}
	/*Insertion du menu*/
	private function affiche_menu(){
		echo $this->menu;
	}
	/*Insertion de la barre de recherche*/
	private function afficher_recherche(){
		echo $this->recherche;
	}
	/*Pied de pages*/
	private function affiche_footer(){
		echo $this->piedDePage;
	}
	/*Fonction permettan l'affichage de la page*/
	public function afficher(){
		?>
			<!DOCTYPE HTML>
			<html lang="fr">
				<head>
					<title><?php echo $this->titre;?></title>
					<meta charset="UTF-8"/>
					<?php $this->charge_style();?>
					<?php $this->affiche_script();?>
				</head>
				<body>
						<header>
							<?php $this->affiche_menu();?>
						</header>
						<div id="wrapper style4" class="contenu">							
							<?php $this->affiche_contenu();?>
						</div>	
						<footer>
							<center>
								<?php $this->affiche_footer();?>
							</center>
						</footer>
				</body>
			</html>
		<?php
	}
}
?>