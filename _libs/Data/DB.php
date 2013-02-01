<?php

/**
 * Quick and dirty wrapper for PDO.
 *
 * @author Brian
 */

namespace Data;

class DB {
    
    protected $log;
    protected $dbh;
    protected $stmt;


    /**
     * 
     * @param string $dsn 
     * @param string $username 
     * @param string $password 
     * @param        $log 
     */
    public function __construct($dsn, $username, $password, $log = null) 
    {
       try
       {
           $this->dbh = new \PDO($dsn, $username, $password);
       }
       catch(PDOException $e)
       {
           //Do something
       }
    }  
    
    /**
     * 
     * @param string $sql
     * @param array $values
     * @return DB
     */
    public function run($sql, array $values=array())
    {
        try 
        {
            $this->stmt = $this->dbh->prepare($sql);

            foreach ($values as $name => $value) 
            {
                if(is_int($value))
                {
                    $this->stmt->bindValue(':'.$name, $value, \PDO::PARAM_INT);
                }
                else
                {
                    $this->stmt->bindValue(':'.$name, $value);    
                }
            }
            
            $this->stmt->execute();
        }
        catch (PDOException $e)
        {
            
        }
        
        return $this;
    }
    
    /**
     * 
     * @return mixed 
     */
    public function fetchRow()
    {
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }


    /**
     * 
     * @return mixed
     */
    public function fetchAll()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * @return int
     */
    public function getLastInsertId()
    {
        return $this->dbh->lastInsertId();
    }
    
    /**
     * 
     */
    public function disconnect()
    {
        $this->dbh = null;
    }
}
