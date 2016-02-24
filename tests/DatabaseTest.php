<?php
/**
 * @file     DatabaseTest.php
 * This class tests the functions in the Database class
 * @package  PotatoORM
 * @author   andrew <andrew.onyango@andela.com>
 * @license  MIT => https://opensource.org/licenses/MIT
 */

namespace PotatoORM\Test;

use PotatoORM\Database;
use PotatoORM\Models\Person;
use PHPUnit_Framework_TestCase;

/**
 *Tests for the Database class
 *
 * @category Test
 * @package  PotatoORM
 */
class DatabaseTest extends PHPUnit_Framework_TestCase
{
    public function testCRUDMethods()
    {
        $db = new Database();
        $id = -1;

        $person = $this->getPerson(["john", "doe"]);
        $id = $db->insert("Person", $person);
        $this->assertFalse($id == -1);

        $db->select("Person", " id = $id");
        $person = $db->singleObject(get_called_class());
        $this->assertEquals("john", $person->{"first_name"});

        $person = $this->getPerson(["jane", "dizoe"]);
        $db->update("Person", $person, " id = $id");
        $db->select("Person", " id = $id");
        $person = $db->singleObject(get_called_class());
        $this->assertEquals("jane", $person->{"first_name"});

        $db->delete("Person");
    }

    private function getPerson($array)
    {
        return [
            "first_name" => $array[0],
            "last_name"  => $array[1]
        ];
    }
}
