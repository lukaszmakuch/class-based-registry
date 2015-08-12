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
class Elephant extends Animal {}
class Plant {}

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
        $this->r->associateValueWithClasses(
            42,
            [Animal::class]
        );
        $this->assertEquals(
            42,
            $this->r->fetchValueByObjects([new Animal()])
        );
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNotExistingKey()
    {
        $this->r->associateValueWithClasses(
            42,
            [Animal::class]
        );
        $this->r->fetchValueByObjects([new Plant()]);
    }
    
    public function testInheritanceSupport()
    {
        $this->r->associateValueWithClasses(
            42,
            [Animal::class]
        );
        $this->assertEquals(
            42,
            $this->r->fetchValueByObjects([new Elephant()])
        );
    }
    
    public function testMoreThanOneClass()
    {
        $this->r->associateValueWithClasses(
            42,
            [Animal::class, Plant::class]
        );
        $this->assertEquals(
            42,
            $this->r->fetchValueByObjects([new Animal(), new Plant()])
        );
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testFewObjectsOfTheSameClassWithWrongNumberOfArgs()
    {
        $this->r->associateValueWithClasses(
            42,
            [Animal::class, Animal::class]
        );
        $this->r->fetchValueByObjects([new Animal()]);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testTooFewObjectsWhenFetching()
    {
        $this->r->associateValueWithClasses(
            42,
            [Animal::class, Plant::class]
        );
        $this->r->fetchValueByObjects([new Animal()]);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testTooManyObjectsWhenFetching()
    {
        $this->r->associateValueWithClasses(
            42,
            [Animal::class]
        );
        $this->r->fetchValueByObjects([new Animal(), new Plant()]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testTooManyObjectsOfTheSameKindWhenFetching()
    {
        $this->r->associateValueWithClasses(
            42,
            [Animal::class]
        );
        $this->r->fetchValueByObjects([new Animal(), new Animal()]);
    }
}
