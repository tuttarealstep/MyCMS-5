<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */
// Grazie a : Vivek Wicky Aswal. (https://twitter.com/#!/VivekWickyAswal)
// Classe basata su https://github.com/indieteq/PHP-MySQL-PDO-Database-Class
class MY_Db {

    private $pdo;
    private $pdo_query;
    private $pdo_connection = false;
    private $parameters;
    private $salva_log;


    public function __construct ()
    {
        $this->Connect();
        $this->parameters = array();
    }

    private function Connect ()
    {
        $PDO_dsn = "mysql:host=".C_HOST.";dbname=".C_DATABASE.";";
        try
        {
            $PDO_user = C_USER;
            $PDO_password = C_PASSWORD;
            $this->pdo = new PDO($PDO_dsn, $PDO_user, $PDO_password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo_connection = true;
        }
        catch (PDOException $e)
        {

            MY_Error::error_die('my_004', $e->getMessage());
            die();
        }
    }

    public function Disconnect ()
    {
        $this->pdo = NULL;
    }

    private function Init($query,$parameters = "")
    {
        if(!$this->pdo_connection) { $this->Connect(); }
        try
        {
            $this->pdo_query = $this->pdo->prepare($query);
            $this->pdo_bind_array($parameters);
            if(!empty($this->parameters)) {
                foreach($this->parameters as $param)
                {
                    $parameters = explode("\x7F",$param);
                    $this->pdo_query->bindParam($parameters[0],$parameters[1]);
                }
            }
            $this->succes = $this->pdo_query->execute();

        }
        catch(PDOException $e)
        {
            $this->SaveLOG($e->getMessage(), $query);
            MY_Error::error_die('my_004', $e->getMessage());
            die();
        }
        $this->parameters = array();
    }
    public function bind($parameters_add, $value)
    {
        $this->parameters[sizeof($this->parameters)] = ":" . $parameters_add . "\x7F" . $value;
    }
    public function pdo_bind_array($parameters_array)
    {
        if(empty($this->parameters) && is_array($parameters_array)) {
            $columns = array_keys($parameters_array);
            foreach($columns as $i => &$column)	{
                $this->bind($column, $parameters_array[$column]);
            }
        }
    }
    public function query($query, $params = null, $fetch_mode = PDO::FETCH_ASSOC)
    {
        $query = trim($query);
        $this->Init($query,$params);
        $rawStatement = explode(" ", $query);

        $statement = strtolower($rawStatement[0]);
        if ($statement === 'select' || $statement === 'show') {
            return $this->pdo_query->fetchAll($fetch_mode);
        }
        elseif ( $statement === 'insert' ||  $statement === 'update' || $statement === 'delete' ) {
            return $this->pdo_query->rowCount();
        } else {
            return NULL;
        }
    }
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
    public function rowCount($query,$params = null)
    {
        $this->Init($query,$params);
        return $this->pdo_query->rowCount();
    }

    public function column($query,$params = null)
    {
        $this->Init($query,$params);
        $Columns = $this->pdo_query->fetchAll(PDO::FETCH_NUM);
        $column = NULL;
        foreach($Columns as $cells) {
            $column[] = $cells[0];
        }
        return $column;
    }

    public function row($query,$params = null,$fetchmode = PDO::FETCH_ASSOC)
    {
        $this->Init($query,$params);
        return $this->pdo_query->fetch($fetchmode);
    }
    public function single($query,$params = null)
    {
        $this->Init($query,$params);
        return $this->pdo_query->fetchColumn();
    }
    public function iftrue($query,$params = null)
    {
        if(!empty($query)){
            if(!empty($params)){
                $controllo = $this->single($query, $params);
                if($controllo >= 1){
                    return true;
                } else {
                    return false;
                }
            } else {
                die();
            }
        } else {
            die();
        }
    }

    private function SaveLOG($msg, $query = "")
    {
        return null;
    }

}