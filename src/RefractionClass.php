<?php
/**
 * The file for the ReflectionClass class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2015 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\Refraction;

/**
 * A refraction class
 *
 * A refraction class is a reflection of a specific class instance, not a generic 
 * class definition like PHP's native ReflectionClass object.
 *
 * A refraction class is mostly visibility safe. A RefractionClass will not name or 
 * return the private methods or private properties of an object's parents, unlike 
 * PHP's native ReflectionClass object. 
 *
 * However, a refraction class will return the private, protected, and public methods 
 * and properties of the refracted class.
 *
 * A refraction class returns refractions, much like PHP's native ReflectionClass 
 * returns reflection methods and properties.
 *
 * @since  0.1.0
 */
class RefractionClass extends \ReflectionClass
{
    /* !Traits */
    
    use Refractor;
    
    
    /* !Magic methods */
    
    /**
     * Called when the object is constructed
     *
     * @param   object  $instance  the instance to refract
     * @return  self
     * @throws  InvalidArgumentException  if $instance is not an object
     * @since   0.1.0
     */
    public function __construct($instance)
    {
        if ( ! is_object($instance)) {
            throw new \InvalidArgumentException(
                __METHOD__."() expects parameter one, instance, to be an object"
            );
        }
        
        parent::__construct($instance);
        
        $this->instance = $instance;
        
        return;
    }
    
    
    /* !Public methods */
    
    /**
     * Returns the method of the refracted class
     *
     * @return  Jstewmc\Refraction\RefractionMethod
     * @throws  InvalidArgumentException  if $name is not a string
     * @throws  OutOfBoundsException      if $name method does not exist
     * @since   0.1.0
     */
    public function getMethod($name)
    {
        if ( ! is_string($name)) {
            throw new \InvalidArgumentException(
                __METHOD__."() expects parameter one, name, to be a string"
            );
        }
        
        foreach ($this->getRefractionMethods() as $method) {
            if ($method->getName() == $name) {
                return $method;
            }   
        }
        
        throw new \OutOfBoundsException(
            __METHOD__."() expects method $name() to exist"
        );
    }
    
    /**
     * Returns the methods of the refracted class
     *
     * Keep in mind, I'll return the private, protected, and public methods of the
     * refracted class, as well as the protected and public methods of any parent
     * class.
     *
     * @return  Jstewmc\Refraction\RefractionMethod[]
     * @since   0.1.0
     */
    public function getMethods()
    {
        return $this->getRefractionMethods();
    }
    
    /**
     * Returns the properties of the refracted class
     *
     * Keep in mind, I'll return the private, protected, and public properties of the
     * refracted class, as well as the protected and public properties of any parent
     * class.
     *
     * @return  Jstewmc\Refraction\RefractionProperty[]
     * @since   0.1.0
     */
    public function getProperties()
    {
        return $this->getRefractionProperties();
    }
    
    /**
     * Returns the property of the refracted class
     *
     * @return  Jstewmc\Refraction\RefractionProperty
     * @throws  InvalidARgumentException  if $name is not a string
     * @throws  OutOfBoundsException      if $name property does not exist
     * @since   0.1.0
     */
    public function getProperty($name)
    {
        if ( ! is_string($name)) {
            throw new \InvalidArgumentException(
                __METHOD__."() expects parameter one, name, to be a string"
            );
        }
        
        foreach ($this->getRefractionProperties() as $property) {
            if ($property->getName() == $name) {
                return $property;
            }   
        }
        
        throw new \OutOfBoundsException(
            __METHOD__."() expects property $name() to exist"
        );
    }
    
    /**
     * Returns true if the refracted class has a $name method (case-insensitive)
     *
     * @param   string  $name  the method's name (case-insensitive)
     * @return  bool
     * @throws  InvalidArgumentException  if $name is not a string
     * @since   0.1.0
     */
    public function hasMethod($name)
    {
        if ( ! is_string($name)) {
            throw new \InvalidArgumentException(
                __METHOD__."() expects parameter one, name, to be a string"
            );
        }
         
        $name = strtolower($name);
         
        foreach ($this->getMethods() as $method) {
            if (strtolower($method->getName()) == $name) {
                return true;
            }
        }
         
        return false;
    }
    
    /**
     * Returns true if the refracted class has a $name property (case-sensitive)
     *
     * @param   string  $name  the property's name (case-sensitive)
     * @return  bool
     * @throws  InvalidArgumentException  if $name is not a string
     * @since   0.1.0
     */
    public function hasProperty($name)
    {
        if ( ! is_string($name)) {
            throw new \InvalidArgumentException(
                __METHOD__."() expects parameter one, name, to be a string"
            );
        }
        
        foreach ($this->getProperties() as $property) {
            if ($property->getName() == $name) {
                return true;
            }
        }
        
        return false;
    }
    
    
    /* !Protected methods */
    
    /**
     * Returns the *actual* methods of the refracted class
     *
     * I'll return the private, protected, and public methods of the refracted class,
     * as well as the protected and public methods of any parent class. That is, I 
     * remove the parents' private methods from the *possible* methods array.
     *
     * Keep in mind, I return *reflections*, not *refractions*.
     *
     * @return  ReflectionMethod[]
     * @since   0.1.0
     */
    protected function getActualMethods()
    {
        // get the object's *possible* methods
        $methods = $this->getPossibleMethods();
        
        // get the current object
        $object = $this;
        
        // while the current object has a parent class
        // keep in mind, getParentClass() will return a ReflectionClass instance, not
        //     a RefractionClass(); thus, the getMethods() method is not recursive
        //
        while (false !== ($parent = $object->getParentClass())) {
            // set the "old" and "new" methods
            $oldMethods = $methods;
            $newMethods = [];
            // get the parent's private methods
            $privates = $parent->getMethods(\ReflectionMethod::IS_PRIVATE);
            // loop through the "old" methods
            foreach ($oldMethods as $oldMethod) {
                // loop through the parent's private methods
                foreach ($privates as $private) {
                    // if the "old" method is not a parent's private method
                    if (
                        $oldMethod->name !== $private->name
                        || $oldMethod->class !== $private->class
                    ) {
                        // append it as a "new" method
                        $newMethods[] = $oldMethod;
                    }
                }
            }
            // save the "new" methods
            $methods = $newMethods;
            // advance to the next parent
            $object = $parent;  
        }
        
        return $methods;
    }
    
    /**
     * Returns the *actual* properties of the refracted class
     *
     * I'll return the private, protected, and public properties of the refracted
     * class, as well as the protected and public properties of any parent class. 
     * That is, I remove the parents' private properties from the *possible* 
     * properties array.
     *
     * Keep in mind, I return *reflections*, not *refractions*.
     *
     * @return  ReflectionProperty[]
     * @since   0.1.0
     */
    protected function getActualProperties()
    {
        // get the object's properties
        $properties = $this->getPossibleProperties();
        
        // get the current object
        $object = $this;
        
        // while the current object has a parent object
        // keep in mind, getParentClass() will return a ReflectionClass instance, not
        //     a RefractionClass(); thus, the getMethods() method is not recursive
        //
        while (false !== ($parent = $object->getParentClass())) {
            // set the "old" and "new" properties
            $oldProperties = $properties;
            $newProperties = [];
            // get the parent's private properties
            $privates = $parent->getProperties(\ReflectionProperty::IS_PRIVATE);
            // loop through the "old" properties
            foreach ($oldProperties as $oldProperty) {
                // loop through the parent's private properties
                foreach ($privates as $private) {
                    // if the property is not a private parent property
                    if (
                        $oldProperty->name !== $private->name
                        || $oldProperty->class !== $private->class
                    ) {
                        // append the "old" property
                        $newProperties[] = $oldProperty;
                    }
                }
            }
            // save the "new" properties
            $properties = $newProperties;
            // advance to the next parent
            $object = $parent;  
        }
        
        return $properties;
    }
    
    /**
     * Returns the *possible* methods of the refracted class
     *
     * I'll return the private, protected, and public methods of the refracted class 
     * as well as the *private*, protected, and public methods of any parent class.
     *
     * Keep in mind, as a wrapper for PHP's native ReflectionClass::getMethods()
     * method, I'll return an array of ReflectionMethods.
     *
     * @return  ReflectionMethod[]
     */
    protected function getPossibleMethods()
    {
        return parent::getMethods();
    }
    
    /**
     * Returns the *possible* properties of the refracted class
     *
     * I'll return the private, protected, and public properties of the refraced 
     * class as well as the *private*, protected, and public properties of any parent 
     * class.
     *
     * Keep in mind, as a wrapped for PHP's native ReflectionClass::getProperties()
     * method, I'll return an array of ReflectionProperties.
     *
     * @return  ReflectionProperty[]
     * @since   0.1.0
     */
    protected function getPossibleProperties()
    {
        return parent::getProperties();
    }
    
    /**
     * Returns the *refracted* methods of the refracted class
     *
     * I convert the native *reflection* methods from the getActualMethods() class to
     * *refraction* methods.
     *
     * @return  Jstewmc\Refraction\RefractionMethod[]
     * @since   0.1.0
     */
    protected function getRefractionMethods()
    {
       $methods = [];
       
       foreach ($this->getActualMethods() as $method) {
           $methods[] = new RefractionMethod($this->instance, $method->getName());
       } 
       
       return $methods;
    }
    
    /**
     * Returns the *refracted* methods of the refracted class
     *
     * I convert the native *reflection* properties of the getActualProperties() 
     * class to *refraction* methods.
     *
     * @return  Jstewmc\Refraction\RefractionProperty[]
     * @since   0.1.0
     */
    protected function getRefractionProperties()
    {
        $properties = [];
        
        foreach ($this->getActualProperties() as $property) {
            $properties[] = new RefractionProperty(
                $this->instance, 
                $property->getName()
            );
        }
        
        return $properties;
    }
}
