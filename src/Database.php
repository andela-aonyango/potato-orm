<?php

namespace PotatoORM;

use PDO;

/**
* Class that assists in data persistence and retrieval
*/
class Database
{
    private $host = "localhost";
    private $username = "potato";
    private $password = "potatopass";
    private $database_name = "test";
    private $database_type = "mysql";

    private $dsn;
    private $database_handler;
    private $error_message;
    private $statement;

    public function __construct()
    {
        $this->setDSN();

        //set options
        $options = [
            PDO::ATTR_PERSISTENT   => true,
            PDO::ATTR_ERRMODE      => PDO::ERRMODE_EXCEPTION
        ];

        //create a new pdo instance
        try {
            $this->database_handler = new PDO(
                $this->dsn,
                $this->username,
                $this->password,
                $options
            );
        } catch (PDOException $e) {
            $this->error_message = $e->getMessage();
        }
    }

    private function setDSN()
    {
        $this->dsn = $this->database_type
            . ":host=" . $this->host
            . ";dbname=" . $this->database_name;
    }

    /**
    * Prepares and returns the statement
    */
    public function prepare($query)
    {
        $this->statement = $this->database_handler->prepare($query);
    }

    /**
    * Binds the values of the parameters in the statement
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

    public function execute()
    {
        $this->statement->execute();
    }

    public function select($table, $where = "", $fields = "*", $order = "", $limit = null, $offset = "" )
    {
        $query = "SELECT $fields FROM $table"
            . ($where ? " WHERE $where " : "")
            . ($limit ? " LIMIT $limit " : "")
            . (($offset && $limit ? " OFFSET $offset " : ""))
            . ($order ? " ORDER BY $order " : "");

        $this->prepare($query);
    }

    public function insert($table, $data)
    {
        ksort($data);

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

    public function update($table, array $data, $where = "")
    {
        ksort($data);
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

    public function delete($table, $where = "", $limit = 1)
    {
        $this->prepare("DELETE FROM $table WHERE $where LIMIT $limit");
        $this->execute();
    }

    /**
    * Returns a collection of objects
    */
    public function objectSet($entityClass)
    {
        $this->execute();
        $this->statement->setFetchMode(PDO::FETCH_CLASS, $entityClass);
        return $this->statement->fetchAll();
    }

    /**
    * Returns an object
    */
    public function singleObject($entityClass)
    {
        $this->execute();
        $this->statement->setFetchMode(PDO::FETCH_CLASS, $entityClass);
        return $this->statement->fetch();
    }
}
