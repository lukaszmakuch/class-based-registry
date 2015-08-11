<?php

/**
 * This file is part of the ClassBasedRegistry library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ClassBasedRegistry\tests;

use lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry;
use PHPUnit_Framework_TestCase;

class Animal {}

/**
 * Tests all registry functionality.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ClassBasedRegistryTest extends PHPUnit_Framework_TestCase
{
    protected $r;
    
    protected function setUp()
    {
        $this->r = new ClassBasedRegistry();
    }


    public function testOneSimpleKey()
    {
        $this->r->associateValueWithClasses(42, [Animal::class]);
        $this->assertEquals(42, $this->r->fetchValueByObjects([new Animal()]));
    }
}