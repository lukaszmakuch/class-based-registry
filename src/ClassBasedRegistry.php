<?php

/**
 * This file is part of the ClassBasedRegistry library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ClassBasedRegistry;

/**
 * Allows to associate a value to one ore more classes
 * and then fetch this value by passing objects that implements these classes.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ClassBasedRegistry
{
    const STOREDVALS_VAL = 0;
    const STOREDVALS_CLASSES = 1;
    
    protected $storedValues;
    
    public function __construct()
    {
        $this->storedValues = [];
    }
    
    public function associateValueWithClasses($valueToStore, array $classes)
    {
        $this->storedValues[] = [
            self::STOREDVALS_VAL => $valueToStore,
            self::STOREDVALS_CLASSES => $classes,
        ];
    }
    
    public function fetchValueByObjects(array $objects)
    {
        foreach ($this->storedValues as $valueToClassesTuple) {
            if ($this->objectsAreExactInstancesOfClasses(
                $objects, 
                $valueToClassesTuple[self::STOREDVALS_CLASSES]
            )) {
                return $valueToClassesTuple[self::STOREDVALS_VAL];
            }
        }
        
        throw new \InvalidArgumentException();
    }
    
    protected function objectsAreExactInstancesOfClasses(array $objects, array $classes)
    {
        $foundClasses = [];
        $foundObjects = [];
        
        foreach ($objects as $singleObject) {
            foreach ($classes as $singleClass) {
                if ($singleObject instanceof $singleClass) {
                    $foundClasses[] = $singleClass;
                    $foundObjects[] = $singleObject;
                }
            }
        }
        
        $remainingObjects = array_filter($objects, function ($sourceObject) use ($foundObjects) {
            return !in_array($sourceObject, $foundObjects, true);
        });
        $remainingClasses = array_diff($classes, $foundClasses);
        
        return (empty($remainingObjects) && empty($remainingClasses));
    }
}
