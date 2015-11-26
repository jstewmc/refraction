<?php
/**
 * The file for the RefractionMethod class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2015 Jack Clayton
 * @license    MIT
 */
 
namespace Jstewmc\Refraction;

/**
 * A refraction method
 *
 * A refraction method is the reflection of a specific method from a specific  
 * object instance, unlike PHP's native reflection method which must be passed an
 * object instance to be invoked or to get a closure.
 *
 * @since  0.1.0
 */
class RefractionMethod extends \ReflectionMethod
{
    /* !Traits */
    
    use Refractor;
    
    
	/* !Magic methods */
	
	/**
	 * Called when the object is constructed
	 *
	 * @param   object  $instance  the method's instance
	 * @param   string  $name      the method's name
	 * @return  self
	 * @throws  InvalidArgumentException  if $instance is not an object
	 * @throws  InvalidArgumentException  if $name is not a string
	 * @throws  OutOfBoundsException      if method does not exist
	 * @throws  OutOfBoundsException      if method does exist but is not visible
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
		
		// try to get the method
		try {
    		$method = (new \ReflectionClass($instance))->getMethod($name);
    		// try to finish constructing the object
    		try {
        		// if the method is not visible, short-circuit
        		if (
        		    $method->isPrivate() 
        		    && $method->class !== get_class($instance)
                ) {
            		throw new \OutOfBoundsException(
                		"Method $name() is defined but not visible to "
                            . get_class($instance)
            		);
        		}
        		
        		// otherwise, chain the parent's constructor
        		parent::__construct($instance, $name);
        		
        		// set the method's accessibility
        		// keep in mind, the method maintains its original visibility outside 
        		//     of this object's scope
        		//
        		$this->setAccessible(true);
        
                // save the method's instance
        		$this->instance = $instance;
        	
        		return;
            } catch (\ReflectionException $e) {
                // otherwise, something else went wrong
                // re-throw the original ReflectionException
                //
                throw $e;
            }
		} catch (\ReflectionException $e) {
    		// otherwise, the method does not exist
    		// throw an OutOfBoundsException
    		//
    		throw new \OutOfBoundsException(
        		"Method $name() must be defined in class ".get_class($instance)
    		);
		}
	}
	
	
	/* !Public methods */
	
	/**
     * Invokes the method with a variable argument list
     *
     * @param   mixed  ...$arguments  the method's arguments
     * @return  mixed
     * @since   0.1.0
     */
	public function invoke(...$arguments)
	{
    	return parent::invokeArgs($this->instance, $arguments);
	}
	
	/**
     * Calls the method with $arguments
     *
     * @param   mixed[]  $arguments  the method's arguments
     * @return  mixed
     * @since   0.1.0
     */
	public function invokeArgs(Array $arguments) 
	{
        return parent::invokeArgs($this->instance, $arguments);	
	}
	
	/**
	 * Returns the method as a closure
	 *
	 * @return  callable
	 * @since   0.1.0
	 */
	public function getClosure()
	{
		return parent::getClosure($this->instance);
	}	
}
