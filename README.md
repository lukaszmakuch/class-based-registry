# ClassBasedRegistry
Allows to associate a value to one or more classes and then fetch this value by passing objects that implement these classes.

## Usage
### Environment
Let's assume that we have classes like these:
```php
class Animal {}
class Elephant extends Animal {}
class Plant {}
```
To get a new instance of the registry create it:
```php
$r = new \lukaszmakuch\ClassBasedRegistry\ClassBasedRegistry();
```
### Simple example
Associate 42 with the Animal class:
```php
$r->associateValueWithClasses(
    42,
    [Animal::class]
);
```
Fetch it by providing any animal...
```php
$r->fetchValueByObjects([new Animal()]); //42
```
... that may be an elephant!
```php
$r->fetchValueByObjects([new Elephant()]); //42
```
### Multiple classes
It is possible to associate value with many classes:
```php
$r->associateValueWithClasses(
    1970,
    [Animal::class, Plant::class]
);
```
While fetching objects may be passed in any order:
```php
$r->fetchValueByObjects([new Plant(), new Elephant()]); //1970
```
### Throwing exceptions
When it's not possible to obtain any value, then an 
\InvalidArgumentException is thrown:
```php
try {
    $r->fetchValueByObjects([new Plant()]);
} catch (\InvalidArgumentException $e) {
  //it was not possible to fetch any value by a plant
}
```
