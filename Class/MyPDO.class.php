<?php
class MyPDO extends PDO
{
    public function __construct($dsn, $user=NULL, $password=NULL)
    {
        parent::__construct($dsn, $user, $password);
		//dire comment on veut traiter les erreurs ici gestion avec les exceptions try catch
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//Il faut ajouter pour gerer les accents et caractères non utf8
		$this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
    }
	
	//méthode exec pour les requêtes de type INSERT, UPDATE et DELETE
    public function exec($sql)
    {		
		return (parent::exec($sql));
    }
	
	//méthode query pour les requêtes de type SELECT
    public function query($sql)
    {   
		$result = parent::query($sql);	
		$result->setFetchMode(PDO::FETCH_OBJ); //resultat de la requête retournée sous la forme d'objets     
        return $result;
    }

	/*
	//méthode prepare de la classe mère qui permet de préparer des requêtes (en deuxième année)
    public function prepare($sql, $options=NULL)
    {
        $statement = parent::prepare($sql);
        if(strpos(strtoupper($sql), 'SELECT') === 0) //requête de type"SELECT"
        {
            $statement->setFetchMode(PDO::FETCH_OBJ);
        }
        return $statement;
    }
	*/
}
?>	
