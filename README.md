# Refraction
Like reflection but with *instances*, not *definitions*.

## Example

Define a sample class:
```php
// a simple sample class
class Foo
{
    public $bar; 
}
```

Get and set `bar` using _reflection_ or _refraction_:
<table>
<tr>
<th>
Using reflection
</th>
<th>
Using refraction
</th>
</tr>
<tr>
<td>
<pre lang="php">
// instantiate a new foo
$foo = new Foo();

// get Foo's reflection
$class = new ReflectionClass($foo);

// get Foo's property
$property = $class->getProperty('bar');

// get and set the property's value 
$property->getValue($foo);
$property->setValue($foo, 'baz');
</pre>
</td>
<td>
<pre lang="php">
// instantiate a new foo
$foo = new Foo();

// get Foo's refraction
$class = new RefractionClass($foo);

// get Foo's property
$property = $class->getProperty('bar');

// get and set the property's value
$property->get(); 
$property->set('baz');
</pre>
</td
</tr>
</table>

There is a small but important difference between _reflection_ and _refraction_ in the last two lines. 

With PHP's native reflection methods, the _reflected_ property is unaware of where it came from, because reflection is based on an object's definition. On the other hand, the _refracted_ property knows where it came from, because refraction is based on a object's instance.

## Reflection

PHP's native [Reflection](http://php.net/manual/en/book.reflection.php) library seems to be considered voodoo by many PHP developers. It's rarely used and poorly understood. Frankly, it was a mystery to me until I started experimenting with it.

I quickly learned reflection is a powerful tool. Reflection allows you to inspect classes, methods, and properties programmatically. At runtime, you can check if a class has a method; you can read a method's DocBlock; you can check a property's visiblity; and, much more. You can do some pretty amazing things with reflection.

Of course, with great power comes great responsibility. Reflection allows you to break encapsulation, a tenet of object-oriented design. You can loop through  an object's parents. You can call protected methods. You can get and set the value of private properties. You can do stuff that is verboten in object-oriented design.

Despite the danger, I've found that reflection is a great tool when used wisely.

## About

Unfortunately, I found reflection has one down side. Reflection inspects an object's definition at compile-time. Even if you instantiate a `ReflectionClass` using an instance of an object, the `ReflectionClass` uses the classname and no more. 

Take the following code:

```php
// define a sample class
class Foo
{
    public $foo;
}

// instantiate a new object
$foo = new Foo();

// get the object's *reflection*
$class = new ReflectionClass($foo);

// get the property
$property = $class->getProperty('foo');

// get the property's value
$value = $property->getValue($foo);   // we have to pass an instance again!

// set the property's value
$value = $property->setValue($foo, 'bars');  // we have to pass an instance again!
``` 

In my mind, I wanted my reflections to remember where they came from. So, I created the *refraction* library.

## Naming convention

You might notice this library's classnames are a little verbose. Each classname (e.g., `RefractionClass`) includes the library's name (i.e., `Refraction`). 

This verbosity isn't ideal, but it's necessary: `class` is a [keyword](http://php.net/manual/en/reserved.keywords.php) and `object` is a [reserved word](http://php.net/manual/en/reserved.other-reserved-words.php) in PHP. 

Rather than obfuscate my class names to `Klass` or `Clazz` (and introduce the corresponding code smell), I decided to follow PHP's lead. In PHP's `Reflection` library each class is prepended with the library name `Reflection` (e.g., `ReflectionClass`, `ReflectionMethod`, etc). If it's good enough for PHP, it's good enough for me!

## Visibility

Unlike PHP's native Reflection library, this library respects ancestor visibility. 

This library will not return the _private_ methods or _private_ properties of a refracted object's parents. In fact, it will pretend as if the parents' private methods and properties do not exist.

Of course, keep in mind, this library will return the _private_, _protected_, and _public_ properties and methods of the refracted object.

For example:

```php
class Base
{
    private function foo()
    {
        return;
    }
}

class Child extends Base
{
    private function bar()
    {
        return;
    }
}

// instantiate an object
$child = new Child();

// instantiate a *reflection*
$reflection = ReflectionClass($child);

$child->hasMethod('foo');  // returns true (what?!)
$child->hasMethod('bar');  // returns true

// instantiate a *refraction*
$refraction = RefractionClass($child);

$child->hasMethod('foo');  // returns false (much better!)
$child->hasMethod('bar');  // returns true
```

## Usage

For the most part, the _refraction_ library follows PHP's _reflection_ library with method names, constructor argument order, etc. In addition, since the _refraction_ classes extend their corresponding _reflection_ classes, they provide all of PHP's native functionality.

Let's define a few simple classes for the usage examples (also, let's assume the classes are appropriately namespaced so the method `foo()` isn't confused as the `Foo` object's constructor):

```php
class Foo
{
    private $foo;
    
    public function foo($value)
    {
        return $value;
    }
}

class Bar extends Foo
{
    private $bar;
    
    public function bar($value)
    {
        return $value;
    }   
}
```

### Classes

To create a new `RefractionClass`, construct the object with an instance of the object you want to refract.

```php
$refraction = new RefractionClass(new Bar());
```

You can check if a property or method exists with the `hasProperty()` and `hasMethod()` methods:

```php
$refraction = new RefractionClass(new Bar());

$refraction->hasProperty('bar');  // returns true
$refraction->hasProperty('foo');  // returns false
$refraction->hasProperty('baz');  // returns false

$refraction->hasMethod('bar');  // returns true
$refraction->hasMethod('foo');  // returns false
$refraction->hasMethod('baz');  // returns false
```

You can get a refracted class property or method with the `getProperty()` and `getMethod()` methods (either method will throw an `OutOfBoundsException` if the method or property does not exist):

```php
$refraction = new RefractionClass(new Bar());

$refraction->getProperty('bar');  // returns RefractionProperty
$refraction->getProperty('foo');  // throws OutOfBoundsException
$refraction->getProperty('baz');  // throws OutOfBoundsException

$refraction->getMethod('bar');  // returns RefractionMethod
$refraction->getMethod('foo');  // throws OutOfBoundsException
$refraction->getMethod('baz');  // throws OutOfBoundsException
```

Finally, you can get all of a class' refracted properties and methods with the `getProperties()` and `getMethods()` methods:

```php
$refraction = new RefractionClass(new Bar());

$refraction->getProperties();  // returns RefractionProperty[]
$refraction->getMethods();     // returns RefractionMethod[]
```

### Methods

A `RefractionMethod` will be returned by the `getMethod()` or `getMethods()` methods of the `RefractionClass`. A `RefractionMethod` can also be instantiated on its own by passing an object instance and a method name. If the method is not defined in the instance or the method is not visible to the instance, an `OutOfBoundsException` will be thrown:

```php
new RefractionClass(new Bar(), 'bar');  // returns RefractionMethod
new RefractionClass(new Bar(), 'foo');  // throws OutOfBoundsException
new RefractionClass(new Bar(), 'baz');  // throws OutOfBoundsException
```

You can invoke a method with the `invoke()` or `invokeArgs()` methods. The former accepts a variadic argument list while the latter accepts an arguments array:

```php
$method = new RefractionClass(new Bar());

$method->invoke(1);        // returns 1
$method->invokeArgs([1]);  // returns 1
```

You can also get a method as a closure with the `getClosure()` method:

```php
$method = new RefractionClass(new Bar());

$method->getClosure();  // returns callable
```

### Properties

A `RefractionProperty` will be returned by the `getProperty()` or `getProperties()` methods of the `RefractionClass`. A `RefractionProperty` can also be instantiated on its own by passing an object instance and a property name. If the property is not defined in the instance or the property is not visible to the instance, an `OutOfBoundsException` will be thrown:

```php
new RefractionProperty(new Bar(), 'bar');  // returns RefractionProperty
new RefractionProperty(new Bar(), 'foo');  // throws OutOfBoundsException  
new RefractionProperty(new Bar(), 'baz');  // throws OutOfBoundsException
```

You can get or set a property with the `get()` and `set()` methods:

```php
$property = new RefractionProperty(new Bar(), 'bar');  

$property->get();  // returns value
$property->set();  // returns void
```

That's about it!

## Author

[Jack Clayton](mailto:clayjs0@gmail.com)

## License

[MIT](https://github.com/jstewmc/refraction/blob/master/LICENSE)


## Version

### dev-master - November 26, 2015 (Happy Thanksgiving!)

* Initial release

