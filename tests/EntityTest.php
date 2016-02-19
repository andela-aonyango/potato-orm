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
    public function testAdd()
    {
        $user = new Person();
        $user->first_name = "add";
        $user->last_name = "user";
        $id = $user->add();

        // check if the last inserted id was returned by the operation
        $this->assertInternalType("int", (int) $id);
        $user->find($id)->remove();
    }

    public function testFind()
    {
        $user = new Person();
        $user->first_name = "find";
        $user->last_name = "user";
        $id = $user->add();

        $testUser = $user->find($id);

        $this->assertEquals("find", $testUser->first_name);
        $testUser->remove();
    }

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
