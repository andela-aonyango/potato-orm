<?php

namespace PotatoORM;

use PDO;
use Dotenv\Dotenv;

/**
* Class that assists in data persistence and retrieval
*/
class Database
{
    private $dsn;
    private $database_handler;
    private $statement;

    /**
     * Sets the DSN for the database and creates a connection
     */
    public function __construct()
    {
        $this->setDSN();

        //set options
        $options = [
            PDO::ATTR_PERSISTENT   => true,
            PDO::ATTR_ERRMODE      => PDO::ERRMODE_EXCEPTION
        ];

        //create a new pdo instance
        $this->database_handler = new PDO(
            $this->dsn,
            getenv("USERNAME"),
            getenv("PASSWORD"),
            $options
        );
    }

    /**
    * Sets the DSN as set in the environment variable file
    */
    private function setDSN()
    {
        $env = new Dotenv(__DIR__ . "/../");
        $env->load();
        
        $this->dsn = getenv("DATABASE_TYPE")
            . ":host=" . getenv("HOST")
            . ";dbname=" . getenv("DATABASE_NAME")
            . ";port=" . getenv("PORT");
    }

    /**
    * Prepares the statement
    *
    * @param string $query  The SQL query to be prepared
    */
    public function prepare($query)
    {
        $this->statement = $this->database_handler->prepare($query);
    }

    /**
    * Binds the values of the parameters in the statement    *
    * @param mixed $param  The parameter that the value will be bound to
    * @param mixed $value  The value to be bound to the parameter
    * @param $type   The PDO SQL data type of the value being bound to the parameter
    */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            if (is_int($value)) {
                $type = PDO::PARAM_INT;
            } elseif (is_bool($value)) {
                $type = PDO::PARAM_BOOL;
            } elseif (is_null($value)) {
                $type = PDO::PARAM_NULL;
            } else {
                $type = PDO::PARAM_STR;
            }
        }

        $this->statement->bindValue($param, $value, $type);
    }

    /**
    * Executes the SQL statement
    */
    public function execute()
    {
        $this->statement->execute();
    }

    /**
     * Prepares a select query to be executed by the execute() method
     * @param string $table  The name of the table to query
     * @param $where  The condition to be used when querying
     */
    public function select($table, $where = "")
    {
        $query = "SELECT * FROM $table"
            . ($where ? " WHERE $where " : "");

        $this->prepare($query);
    }

    /**
     * Inserts a new record in the database and returns the last inserted ID
     * @param string $table  The name of the table to insert the record into
     * @param array $data    The information to insert
     * @return int
     */
    public function insert($table, $data)
    {
        $field_names = implode(",", array_keys($data));
        $field_values = ":" . implode(", :", array_keys($data));

        $query = "INSERT INTO $table ($field_names) VALUES ($field_values)";

        $this->prepare($query);

        foreach ($data as $key => $value) {
            $this->bind(":$key", $value);
        }

        $this->execute();

        return $this->database_handler->lastInsertId();
    }

    /**
     * Updates a record in the database
     * @param string $table  The table to update
     * @param array $data    The updated information in the form of an array
     * @param $where  The condition that needs to be true in order to update
     */
    public function update($table, array $data, $where = "")
    {
        $field_details = null;

        foreach ($data as $key => $value) {
            $field_details .= "$key = :$key,";
        }
        $field_details = rtrim($field_details, ",");

        $query = "UPDATE $table SET $field_details "
            . ($where ? " WHERE $where " : "");

        $this->prepare($query);

        foreach ($data as $key => $value) {
            $this->bind(":$key", $value);
        }

        $this->execute();
    }

    /**
     * Deletes a record or records from the database
     * @param string $table  The table to delete the record(s) from
     * @param $where  The condition that needs to be true in order to delete
     * @param int $limit
     */
    public function delete($table, $where = "", $limit = 1)
    {
        if (!empty(trim($where))) {
            $statement = "DELETE FROM $table WHERE $where LIMIT $limit";
        } else {
            $statement = "DELETE FROM $table";
        }

        $this->prepare($statement);
        $this->execute();
    }

    /**
     * Retrieves all records from the corresponding table in the
     * database and returns them as an array of objects
     * @return array
     */
    public function objectSet($entityClass)
    {
        $this->execute();
        $this->statement->setFetchMode(PDO::FETCH_CLASS, $entityClass);
        return $this->statement->fetchAll();
    }

    /**
     * Retrieves a record from the database based on its ID
     * and returns it in the form of the corresponding object
     * @return object
     */
    public function singleObject($entityClass)
    {
        $this->execute();
        $this->statement->setFetchMode(PDO::FETCH_CLASS, $entityClass);
        return $this->statement->fetch();
    }
}
