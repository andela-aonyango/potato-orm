<?php
/**
 * @file     PersonTest.php
 * This class tests the functions in the Person class
 * @package  PotatoORM
 * @author   andrew <andrew.onyango@andela.com>
 * @license  MIT => https://opensource.org/licenses/MIT
 */

namespace PotatoORM\Test;

use PotatoORM\Models\Person;
use PHPUnit_Framework_TestCase;

/**
 *Tests for the Person class
 *
 * @category Test
 * @package  PotatoORM
 */
class PersonTest extends PHPUnit_Framework_TestCase
{
    public function testPersonProperties()
    {
        $person = new Person();
        $person->first_name = "john";
        $person->last_name = "doe";
        $person->age = 23;
        $person->gender = "female";

        $this->assertEquals("john", $person->first_name);
        $this->assertEquals("doe", $person->last_name);
        $this->assertEquals(23, $person->age);
        $this->assertEquals("female", $person->gender);
    }
}
