<?php
/**
 * The file for the RefractionProperty class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2015 Jack Clayton
 * @license    MIT
 */
	
namespace Jstewmc\Refraction;

/**
 * A refraction property
 *
 * A refraction property is the reflection of a specific property from a specific 
 * object instance, unlike PHP's native ReflectionProperty which must be passed an
 * object instance to get or set the property's value.
 *
 * @since  0.1.0
 */
class RefractionProperty extends \ReflectionProperty
{
    /* !Traits */
    
    use Refractor;
    
    
	/* !Magic methods */
	
	/**
	 * Called when the object is constructed
	 *
	 * @param   object  $instance  the property's instance
	 * @param   string  $name      the property's name
	 * @return  self
	 * @throws  InvalidArgumentException  if $instance is not an object
	 * @throws  InvalidArgumentException  if $name is not a string
	 * @throws  OutOfBoundsException      if property does not exist
	 * @throws  OutOfBoundsException      if property does exist but is not visible
	 * @throws  ReflectionException       if something else goes wrong
	 * @since   0.1.0
	 */
	public function __construct($instance, $name)
	{
    	// if $instance is not an object, short-circuit
    	if ( ! is_object($instance)) {
            throw new \InvalidArgumentException(
                __METHOD__."() expects parameter one, instance, to be an object"
            );	
    	}
    	
		// if $name is not a string, short-circuit
		if ( ! is_string($name)) {
			throw new \InvalidArgumentException(
				__METHOD__."() expects parameter two, name, to be a string"
			);
		}
		
		// try to get the property
		try {
    		$property = (new \ReflectionClass($instance))->getProperty($name);
    		// try to finish constructing the object
    		try {
        		// if the property is not visible, short-circuit
                if (
                    $property->isPrivate() 
                    && $property->class !== get_class($instance)
                ) {
                    throw new \OutOfBoundsException(
                		"Property '$name' is defined but not visible to "
                            . get_class($instance)
            		);
                }
                
                // chain the parent's constructor
                parent::__construct($instance, $name);
		
        		// otherwise, set the property to accessible
        		// keep in mind, the property maintains its original visibility
        		//     outside of this object's scope
        		//
        		$this->setAccessible(true);
        		
        		// set the data
        		$this->instance = $instance;
        		
        		return;
                		
    		} catch (\ReflectionException $e) {
        		// otherwise, an exception occured
        		// re-throw the original exception
        		//
        		throw $e;
    		}
		} catch (\ReflectionException $e) {
    		// otherwise, the property does not exist
    		// throw an OutOfBoundsException
    		//
    		throw new \OutOfBoundsException(
				"Property '$name' must be defined in class ". get_class($instance)
			);
		}
	}
	
	
	/* !Public methods */
	
	/**
	 * Returns the property's value
	 *
	 * @return  mixed
	 * @since   0.1.0
	 */
	public function get()
	{
		return parent::getValue($this->instance);
	}
	
	/**
	 * Sets the property's value
	 *
	 * @param   mixed  $value  the property's value
	 * @return  void
	 * @since   0.1.0
	 */
	public function set($value)
	{
		parent::setValue($this->instance, $value);
		
		return;
	}
}
