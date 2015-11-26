<?php
/**
 * The file for the RefractionClassTest class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2015 Jack Clayton
 * @license    MIT
 */
 
namespace Jstewmc\Refraction;

use Jstewmc\Refraction\Tests\Base;
use Jstewmc\Refraction\Tests\Child;

/**
 * The test suite for the RefractionClass
 * 
 * @since  0.1.0
 */
class RefractionClassTest extends \PHPUnit_Framework_TestCase
{
    /* !__construct() */
    
    /**
     * __construct() should throw InvalidArgumentException if $instance is not object
     */
    public function test_construct_throwsInvalidArgumentException_ifInstanceNotObject()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        new RefractionClass(999);
        
        return;
    }
    
    
    /* !getMethod() */
    
    /**
     * getMethod() should throw InvalidArgumentException if $name is not string
     */
    public function test_getMethod_throwsInvalidArgumentException_ifNameIsNotString()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        (new RefractionClass(new \StdClass()))->getMethod(999);
        
        return;
    }
    
    /**
     * getMethod() should throw OutOfBoundsException if method does not exist 
     */
    public function test_getMethod_throwsOutOfBoundsException_ifMethodDoesNotExist()
    {
        $this->setExpectedException('OutOfBoundsException');
        
        (new RefractionClass(new \StdClass()))->getMethod('foo');
        
        return;
    }
    
    /**
     * getMethod() should return method if method does exist
     */
    public function test_getMethod_returnsMethod_ifMethodDoesExist()
    {
        $instance = new Child();
        
        return $this->assertEquals(
            new RefractionMethod($instance, Child::METHOD_PUBLIC),
            (new RefractionClass($instance))->getMethod(Child::METHOD_PUBLIC)
        );
    }
    
    
    /* !getMethods() */
    
    /**
     * getMethods() should return array if methods do not exist
     */
    public function test_getMethods_returnsArray_ifMethodsDoNotExist()
    {
        return $this->assertEquals(
            [], 
            (new RefractionClass(new \StdClass()))->getMethods()
        );
    }
    
    /**
     * getMethods() should return array if methods do exist
     */
    public function test_getMethods_returnsArray_ifMethodsDoExist()
    {
        $instance = new Child();
        
        // keep in mind, the methods this function *does* return are almost as 
        //     important as the methods this function *does not* return; this 
        //     function should not return Base::METHOD_PRIVATE!
        //
        $expected = [
            new RefractionMethod($instance, Base::METHOD_PROTECTED),
            new RefractionMethod($instance, Base::METHOD_PUBLIC),
            new RefractionMethod($instance, Child::METHOD_PRIVATE),
            new RefractionMethod($instance, Child::METHOD_PROTECTED),
            new RefractionMethod($instance, Child::METHOD_PUBLIC),
            new RefractionMethod($instance, Child::METHOD_EXTENDED)
        ];
        
        $actual = (new RefractionClass($instance))->getMethods();
        
        $this->assertEquals(count($expected), count($actual));
        $this->assertEquals(0, count(array_diff($actual, $expected)));
        
        return;
    }
    
    
    /* !getProperty() */
    
    /**
     * getProperty() should throw InvalidArgumentException if $name is not string
     */
    public function test_getProperty_throwsInvalidArgumentException_ifNameIsNotString()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        (new RefractionClass(new \StdClass()))->getProperty(999);
        
        return;
    }
    
    /**
     * getProperty() should throw OutOfBoundsException if property does not exist
     */
    public function test_getProperty_throwsOutOfBoundsException_ifPropertyDoesNotExist()
    {
        $this->setExpectedException('OutOfBoundsException');
        
        (new RefractionClass(new \StdClass()))->getProperty('foo');
        
        return;
    }
    
    /**
     * getProperty() should return property if property does exist
     */
    public function test_getProperty_returnsProperty_ifPropertyDoesExist()
    {
        $instance = new Child();
        
        return $this->assertEquals(
            new RefractionProperty($instance, Child::PROPERTY_PUBLIC),
            (new RefractionClass($instance))->getProperty(Child::PROPERTY_PUBLIC)
        );   
    }
    
    
    /* !getProperties() */
    
    /**
     * getProperties() should return array if properties do not exist
     */
    public function test_getProperties_returnsArray_ifPropertiesDoNotExist()
    {
        return $this->assertEquals(
            [],
            (new RefractionClass(new \StdClass()))->getProperties()
        );
    }
    
    /**
     * getProperties() should return array if properties do exist
     */
    public function test_getProperties_returnsArray_ifPropertiesDoExist()
    {
        $instance = new Child();
        
        // keep in mind, the properties this function *does* return are almost as 
        //     important as the properties this function *does not* return; this 
        //     function should not return Base::PROPERTY_PRIVATE!
        //
        $expected = [
            new RefractionProperty($instance, Base::PROPERTY_PROTECTED),
            new RefractionProperty($instance, Base::PROPERTY_PUBLIC),
            new RefractionProperty($instance, Child::PROPERTY_PRIVATE),
            new RefractionProperty($instance, Child::PROPERTY_PROTECTED),
            new RefractionProperty($instance, Child::PROPERTY_PUBLIC),
            new RefractionProperty($instance, Child::PROPERTY_EXTENDED)
        ];
        
        $actual = (new RefractionClass($instance))->getProperties();
                
        $this->assertEquals(count($expected), count($actual));
        $this->assertEquals(0, count(array_diff($actual, $expected)));
        
        return;
    }
    
    
    /* !hasMethod() */
    
    /**
     * hasMethod() should throw InvalidArgumentException if $name is not string
     */
    public function test_hasMethod_throwsInvalidArgumentException_ifNameIsNotString()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        (new RefractionClass(new \StdClass()))->hasMethod(999);
        
        return;
    }
    
    /**
     * hasMethod() should return false if the method does not exist
     */
    public function test_hasMethod_returnsFalse_ifMethodDoesNotExist()
    {
        return $this->assertFalse(
            (new RefractionClass(new \StdClass()))->hasMethod('foo')
        );
    }
    
    /**
     * hasMethod() should return false if the method does exist but is not visible
     */
    public function test_hasMethod_returnsFalse_ifMethodIsNotVisible()
    {
        return $this->assertFalse(
            (new RefractionClass(new Child()))->hasMethod(Base::METHOD_PRIVATE)
        );
    }
    
    /**
     * hasMethod() should return true if the method does exist and is visible
     */
    public function test_hasMethod_returnsTrue_ifMethodIsVisible()
    {
        return $this->assertTrue(
            (new RefractionClass(new Child()))->hasMethod(Child::METHOD_PUBLIC)
        );
    }
    
    
    /* !hasProperty() */
    
    /**
     * hasProperty() should throw an InvalidArgumentException if $name is not string
     */
    public function test_hasProperty_throwsInvalidArgumentException_ifNameIsNotString()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        (new RefractionClass(new \StdClass()))->hasProperty(999);
        
        return;
    }
    
    /**
     * hasProperty() should return false if property does not exist
     */
    public function test_hasProperty_returnsFalse_ifPropertyDoesNotExist()
    {
        return $this->assertFalse(
            (new RefractionClass(new \StdClass()))->hasProperty('foo')
        );
    }
    
    /**
     * hasProperty() should return false if property does exist but is not visible
     */
    public function test_hasProperty_returnsFalse_ifPropertyIsNotVisible()
    {
        return $this->assertFalse(
            (new RefractionClass(new Child()))->hasProperty(Base::PROPERTY_PRIVATE)
        );
    }
    
    /**
     * hasProperty() should return true if property does exist and is visible
     */
    public function test_hasProperty_returnTrue_ifPropertyIsVisible()
    {
        return $this->assertTrue(
            (new RefractionClass(new Child()))->hasProperty(Child::PROPERTY_PUBLIC)
        );
    }
}
