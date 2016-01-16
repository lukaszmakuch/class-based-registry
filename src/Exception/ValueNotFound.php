<?php

/**
 * This file is part of the ClassBasedRegistry library.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace lukaszmakuch\ClassBasedRegistry\Exception;

/**
 * Thrown when it's not possible to fetch a value because 
 * there are no objects associated to it.
 *
 * @author Łukasz Makuch <kontakt@lukaszmakuch.pl>
 */
class ValueNotFound extends \InvalidArgumentException
{
}
