<?php

namespace PotatoORM\Models;

use PotatoORM\Entity;

class Person extends Entity
{
    public $id;
    public $first_name;
    public $last_name;
    public $age;
    public $gender;

    public $entity_table = "Person";
    public $entity_class = "Person";
    public $db_fields = ["id", "first_name", "last_name", "age", "gender"];
    public $primary_keys = ["id"];
}
