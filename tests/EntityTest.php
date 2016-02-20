<?php
/**
 * @file     EntityTest.php
 * This class tests the functions in the Database class
 * @package  PotatoORM
 * @author   andrew <andrew.onyango@andela.com>
 * @license  MIT => https://opensource.org/licenses/MIT
 */

namespace PotatoORM\Test;

use PotatoORM\Database;
use PotatoORM\Entity;
use PotatoORM\Models\Person;
use PHPUnit_Framework_TestCase;

/**
 *Tests for the Entity class and classes that derive from it
 *
 * @category Test
 * @package  PotatoORM
 */
class EntityTest extends PHPUnit_Framework_TestCase
{
    /**
    * Tests if the add() function inserts a record into the database
    */
    public function testAdd()
    {
        $user = new Person();
        $user->first_name = "add";
        $user->last_name = "user";
        $id = $user->add();

        // if the insertion was successful, the last inserted id was assigned
        $this->assertInternalType("int", (int) $id);
        $user->find($id)->remove();
    }

    /**
    * Tests if the find() function returns a particular record
    */
    public function testFind()
    {
        $user = new Person();
        $user->first_name = "find";
        $user->last_name = "user";
        $id = $user->add();

        // use a different object retrieved from the database
        // to ensure that we'll be comparing an object from the database
        $testUser = $user->find($id);

        $this->assertEquals("find", $testUser->first_name);
        $testUser->remove();
    }

    /**
    * Tests if the findAll() function returns all records in the table
    */
    public function testFindAll()
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = new Person();
            $user->first_name = "findAll";
            $user->last_name = "user{$i}";
            $id = $user->add();
        }

        $users = $user->findAll();

        $this->assertEquals(10, count($users));

        foreach ($users as $user) {
            $user->remove();
        }
    }

    /**
    * Tests if a record is retrievable after being remove()d
    */
    public function testRemove()
    {
        $user = new Person();
        $user->first_name = "test";
        $user->last_name = "user";
        $id = $user->add();

        $testUser = $user->find($id);
        $testUser->remove();

        // find() returns false if the record was not found in the database
        $this->assertFalse($testUser->find($id));
    }
}
