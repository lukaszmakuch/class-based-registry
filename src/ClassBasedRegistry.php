<?php

/**
 * This file is part of the ClassBasedRegistry library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ClassBasedRegistry;

/**
 * Allows to associate a value to one or more classes
 * and then fetch this value by passing objects that implements these classes.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ClassBasedRegistry
{
    /**
     * Key representing the stored value.
     */
    const STOREDVALS_VAL = 0;
    
    /**
     * Key representing array of associated classes.
     */
    const STOREDVALS_CLASSES = 1;
    
    /**
     * Holds stored values together with classes they are associated with.
     * 
     * @var array like 
     * [
     * self::STOREDVALS_VAL => $value,
     * self::STOREDVALS_CLASSES => ["Class1" , "Class2", "Class3"]
     * ]
     */
    protected $valuesWithAssociatedClasses;
    
    public function __construct()
    {
        $this->valuesWithAssociatedClasses = [];
    }
    
    /**
     * Associates the given value with one or more classes.
     * 
     * @param mixed $valueToStore any value to store
     * @param String[] $classes
     * 
     * @return null
     */
    public function associateValueWithClasses($valueToStore, array $classes)
    {
        $this->valuesWithAssociatedClasses[] = [
            self::STOREDVALS_VAL => $valueToStore,
            self::STOREDVALS_CLASSES => $classes,
        ];
    }
    
    /**
     * Fetches a value previously associated with classes the given objects implement.
     * 
     * Inheritence is taken into account.
     * The number of given objects and the number of classes associated with the value
     * must be equal.
     * 
     * @param array $objects 
     * 
     * @return mixed previously stored value
     * @throws \InvalidArgumentException when it's not possible to fetch any value
     */
    public function fetchValueByObjects(array $objects)
    {
        foreach ($this->valuesWithAssociatedClasses as $valueToClassesTuple) {
            if ($this->objectsAreExactInstancesOfClasses(
                $objects, 
                $valueToClassesTuple[self::STOREDVALS_CLASSES]
            )) {
                return $valueToClassesTuple[self::STOREDVALS_VAL];
            }
        }
        
        throw new \InvalidArgumentException();
    }
    
    /**
     * Checks whether all of the given objects implement all of the given classes.
     * 
     * @param array $objects
     * @param String[] $classes
     * 
     * @return boolean
     */
    protected function objectsAreExactInstancesOfClasses(array $objects, array $classes)
    {
        if (count($objects) !== count($classes)) { 
            return false;
        }
        
        $remainingObjects = new \SplObjectStorage();
        foreach ($objects as $singleObj) {
            $remainingObjects->attach($singleObj);
        }
        
        return $this->objectsAreExactInstancesOfClassesImpl($remainingObjects, $classes);
    }
    
    protected function objectsAreExactInstancesOfClassesImpl(\SplObjectStorage $remainingObjects, $classes)
    {
        $classToFind = array_pop($classes);
        $objectFound = false;
        foreach ($remainingObjects as $obj) {
            if ($obj instanceof $classToFind) {
                $remainingObjects->detach($obj);
                $objectFound = true;
                break;
            }
        }
        
        if (false === $objectFound) {
            return false;
        }
        
        if (0 === $remainingObjects->count()) {
            return true;
        }
        
        return $this->objectsAreExactInstancesOfClassesImpl($remainingObjects, $classes);
    }
}
