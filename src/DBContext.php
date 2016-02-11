<?php

namespace PotatoORM;

class DBContext
{
    private $db;
    private $entities = [];

    public function __construct()
    {
        $this->db = new Database();
    }

    public function find(
        $entity, $conditions=[], $fields="", $order="",
        $limit=null, $offset="")
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
        $this->db->select(
            $entity->entity_table, $where, $fields, $order, $limit, $offset);
        
    }
}