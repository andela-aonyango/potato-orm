<?php
// Example usage of this ORM

require "vendor/autoload.php";

use PotatoORM\Models\Person;

$person = new Person();
$person->first_name = "yua";
$person->last_name = "madha";
$person->age = 73;

// the add() method inserts an object and returns the last inserted id
$id = $person->add();

// retrieve the just-added person
$test = $person->find($id);

print_r($test);

$test->gender = "female";
$test->update();

// is the update successful
print_r($test->find($id));

// delete the person from the database
$test->remove();

// retrieve all people
print_r($person->findAll());
