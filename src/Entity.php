<?php
/**
 * @file     Entity.php
 * This class is a model that can be inherited by other classes
 * and used to persist and retrieve objects from a database
 * @package  PotatoORM
 * @author   andrew <andrew.onyango@andela.com>
 * @license  MIT => https://opensource.org/licenses/MIT
 */

namespace PotatoORM;

/**
 * @category Class
 * @package  PotatoORM
 */
class Entity
{
    private $db;

    /**
     * Instantiates a Database object
     *
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Adds a record to the database and returns the last inserted ID
     * @return int
     */
    public function add()
    {
        foreach ($this->db_fields as $key) {
            $data[$key] = $this->$key;
        }

        return $this->db->insert($this->entity_table, $data);
    }

    /**
     * Updates an existing record in the database
     */
    public function update()
    {
        foreach ($this->db_fields as $key) {
            if (!is_null($this->$key)) {
                $data[$key] = $this->$key;
            }
        }

        $where = "";
        foreach ($this->primary_keys as $key) {
            $where .= " $key = " . $this->$key . " &&";
        }

        $where = rtrim($where, "&");
        $this->db->update($this->entity_table, $data, $where);
    }

    /**
     * Deletes a record from the database
     * @return int
     */
    public function remove()
    {
        $where = "";
        foreach ($this->primary_keys as $key) {
            $where .= " $key = " . $this->$key . " &&";
        }

        $where = rtrim($where, "&");
        $this->db->delete($this->entity_table, $where);
    }

    /**
     * Retrieves a record from the database based on its ID
     * and returns it in the form of the corresponding object
     * @return object
     */
    public function find($id)
    {
        $this->db->select($this->entity_table, " id = $id");

        return $this->db->singleObject(get_called_class());
    }

    /**
     * Retrieves all records from the corresponding table in the
     * database and returns them as an array of objects
     * @return array
     */
    public function findAll()
    {
        $this->db->select($this->entity_table);

        return $this->db->objectSet(get_called_class());
    }
}
