<?php
/**
 * The file for the RefractionMethodTest class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2015 Jack Clayton
 * @license    MIT
 */
 
namespace Jstewmc\Refraction;

use Jstewmc\Refraction\Tests\Base;
use Jstewmc\Refraction\Tests\Child;

/**
 * The test suite for the RefractionMethod class
 * 
 * @since  0.1.0
 */
class RefractionMethodTest extends \PHPUnit_Framework_TestCase
{
    /* !__construct() */
    
    /**
     * __construct() should throw InvalidArgumentException if $instance is not an object
     */
    public function test_construct_throwsInvalidArgumentException_ifInstanceIsNotObject()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        new RefractionMethod(999, 'foo');
        
        return;
    }
    
    /**
     * __construct() should throw InvalidArgumentException if $name is not a string
     */
    public function test_construct_throwsInvalidArgumentException_ifNameIsNotString()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        new RefractionMethod(new \StdClass(), 999);
        
        return;
    }
    
    /**
     * __construct() should throw OutOfBoundsException if method does not exist
     */
    public function test_construct_throwsOutOfBoundsException_ifMethodDoesNotExist()
    {
        $this->setExpectedException('OutOfBoundsException');
        
        new RefractionMethod(new \StdClass(), 'foo');
        
        return;
    }
    
    /**
     * __construct() should throw OutOfBoundsException if method is not visible
     */
    public function test_construct_throwsOutOfBoundsException_ifMethodIsNotVisible()
    {
        $this->setExpectedException('OutOfBoundsException');
        
        new RefractionMethod(new Child(), Base::METHOD_PRIVATE);
        
        return;
    }
    
    
    /* !invoke() */
    
    /**
     * invoke() should return result
     */
    public function test_invoke_returnsResult()
    {
        $instance = new Child();
        
        $method = new RefractionMethod($instance, Child::METHOD_PUBLIC);
        
        $value = 1;
        
        $this->assertEquals($value, $method->invoke($value));
        
        return;
    }
    
    
    /* !invokeArgs() */
    
    /**
     * invokeArgs() should return result
     */
    public function test_invokeArgs_returnsResult()
    {
        $instance = new Child();
        
        $method = new RefractionMethod($instance, Child::METHOD_PUBLIC);
        
        $value = 1;
        
        $this->assertEquals($value, $method->invokeArgs([$value]));
        
        return;
    }
    
    
    /* !getClosure() */
    
    /**
     * getClosure() should return callable
     */
    public function test_getClosure_returnsCallable()
    {
        $method = new RefractionMethod(new Child(), Child::METHOD_PUBLIC);
        
        return $this->assertTrue(is_callable($method->getClosure()));
    }
}
