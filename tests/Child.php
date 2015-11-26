<?php
/**
 * The file for the Child class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2015 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\Refraction\Tests;

/**
 * A child class for tests
 *
 * @since  0.1.0
 */
class Child extends Base
{
     /* !Constants */
    
    /**
     * @var  string  the name of the class' private method
     */
    const METHOD_PRIVATE = 'childPrivateMethod';
    
    /**
     * @var  string  the name of the class' protected method
     */
    const METHOD_PROTECTED = 'childProtectedMethod';
    
    /**
     * @var  string  the name of the class' public method
     */
    const METHOD_PUBLIC = 'childPublicMethod';
    
    /**
     * @var  string  the name of the class' method that'll be extended
     */
    const METHOD_EXTENDED = 'extendedMethod';
    
    /**
     * @var  string  the name of the class' private property
     */
    const PROPERTY_PRIVATE = 'childPrivateProperty';
    
    /**
     * @var  string  the name of the class' protected property
     */
    const PROPERTY_PROTECTED = 'childProtectedProperty';
    
    /**
     * @var  string  the name of the class' public property
     */
    const PROPERTY_PUBLIC = 'childPublicProperty';
    
    /**
     * @var  string  the name of the class' property that'll be extended
     */
    const PROPERTY_EXTENDED = 'extendedProperty';
    
    
    /* !Properties */
    
    private $childPrivateProperty = 'childPrivateProperty';
    
    protected $childProtectedProperty = 'childProtectedProperty';
    
    public $childPublicProperty = 'childPublicProperty';
    
    public $extendedProperty = 'childExtendedProperty';
    
    
    /* !Methods */
    
    private function childPrivateMethod($value)
    {
        return $value;
    }
    
    protected function childProtectedMethod($value)
    {
        return $value;
    }
    
    public function childPublicMethod($value)
    {
        return $value;
    }
    
    public function extendedMethod($value) 
    {
        return $value;
    }    
}
