<?php
/**
 * The file for the RefractionPropertyTest class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2015 Jack Clayton
 * @license    MIT
 */
 
namespace Jstewmc\Refraction;

use Jstewmc\Refraction\Tests\Base;
use Jstewmc\Refraction\Tests\Child;

/**
 * The test suite for the RefractionProperty class
 * 
 * @since  0.1.0
 */
class RefractionPropertyTest extends \PHPUnit_Framework_TestCase
{
    /* !__construct() */
    
    /**
     * __construct() should throw InvalidArgumentException if $instance is not object
     */
    public function test_construct_throwsInvalidArgumentException_ifInstanceIsNotObject()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        new RefractionProperty(999, 'foo');
        
        return;
    }
    
    /**
     * __construct() should throw InvalidArgumentException if $name is not string
     */
    public function test_construct_throwsInvalidArgumentException_ifNameIsNotString()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        new RefractionProperty(new \StdClass(), 999);
        
        return;
    }
    
    /**
     * __construct() should throw OutOfBoundsException if property does not exist
     */
    public function test_construct_throwsOutOfBoundsException_ifPropertyDoesNotExist()
    {
        $this->setExpectedException('OutOfBoundsException');
        
        new RefractionProperty(new \StdClass(), 'foo');
        
        return;
    }
    
    /**
     * __construct() should throw OutOfBoundsException if property is not visible
     */
    public function test_construct_throwsOutOfBoundsException_ifPropertyIsNotVisible()
    {
        $this->setExpectedException('OutOfBoundsException');
        
        new RefractionProperty(new Child(), Base::PROPERTY_PRIVATE);
        
        return;
    }
    
    
    /* !get() */
    
    /**
     * get() should return the property's value
     */
    public function test_get_returnsValue()
    {
        return $this->assertEquals(
            Child::PROPERTY_PROTECTED, 
            (new RefractionProperty(new Child(), Child::PROPERTY_PROTECTED))->get()
        );
    }
    
    
    /* !set() */
    
    /**
     * set() should return void
     */
    public function test_set_returnsVoid()
    {
        $instance = new Child();
        
        $property = new RefractionProperty($instance, Child::PROPERTY_PUBLIC);
        
        $this->assertNull($property->set('foo'));
        $this->assertEquals('foo', $instance->{Child::PROPERTY_PUBLIC});
        
        return;   
    }
}
    