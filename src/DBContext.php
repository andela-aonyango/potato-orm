<?php

require_once "Database.php";
require_once "EntityState.php";

class DBContext
{
    private $db;
    private $entities = [];

    public function __construct()
    {
        $this->db = new Database();
    }

    public function find($entity, $conditions = [], $fields = "*", $order = "", $limit = null, $offset = "")
    {
        $where = "";

        foreach ($conditions as $key => $value) {
            if (is_string($value)) {
                $where .= " $key = '$value' &&";
            } else {
                $where .= " $key = $value &&";
            }
        }

        $where = rtrim($where, "&");
        $this->db->select($entity->entity_table, $where, $fields, $order, $limit, $offset);

        return $this->db->singleObject($entity->entity_class);
    }

    public function findAll($entity, $conditions = [], $fields = "*", $order = "", $limit = null, $offset = "")
    {
        $where = "";

        foreach ($conditions as $key => $value) {
            if (is_string($value)) {
                $where .= " $key = '$value' &&";
            } else {
                $where .= " $key = $value &&";
            }
        }

        $where = rtrim($where, "&");
        $this->db->select($entity->entity_table, $where, $fields, $order, $limit, $offset);

        return $this->db->objectSet($entity->entity_class);
    }

    public function save()
    {
        foreach ($this->entities as $entity) {
            switch ($entity->entity_state) {
                case EntityState::CREATE:
                    foreach ($entity->db_fields as $key) {
                        $data[$key] = $entity->$key;
                    }

                    $this->db->insert($entity->entity_table, $data);
                    break;

                case EntityState::UPDATE:
                    foreach ($entity->db_fields as $key) {
                        if (!is_null($entity->$key)) {
                            $data[$key] = $entity->$key;
                        }
                    }

                    $where = "";
                    foreach ($entity->primary_keys as $key) {
                        $where .= " $key = " . $entity->$key . " &&";
                    }

                    $where = rtrim($where, "&");
                    $this->db->update($entity->entity_table, $data, $where);
                    break;

                case EntityState::DELETE:
                    $where = "";
                    foreach ($entity->primary_keys as $key) {
                        $where .= " $key = " . $entity->$key . " &&";
                    }

                    $where = rtrim($where, "&");
                    $this->db->delete($entity->entity_table, $where);
                    break;
            }
        }
        unset($data);
        unset($this->entities);
    }

    public function add($entity)
    {
        $entity->entity_state = EntityState::CREATE;
        array_push($this->entities, $entity);
    }

    public function update($entity)
    {
        $entity->entity_state = EntityState::UPDATE;
        array_push($this->entities, $entity);
    }

    public function remove($entity)
    {
        $entity->entity_state = EntityState::DELETE;
        array_push($this->entities, $entity);
    }
}
