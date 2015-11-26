<?php
/**
 * The file for the Base class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2015 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\Refraction\Tests;

/**
 * A base class for tests
 *
 * @since  0.1.0
 */
class Base
{
    /* !Constants */
    
    /**
     * @var  string  the name of the class' private method
     */
    const METHOD_PRIVATE = 'basePrivateMethod';
    
    /**
     * @var  string  the name of the class' protected method
     */
    const METHOD_PROTECTED = 'baseProtectedMethod';
    
    /**
     * @var  string  the name of the class' public method
     */
    const METHOD_PUBLIC = 'basePublicMethod';
    
    /**
     * @var  string  the name of the class' method that'll be extended
     */
    const METHOD_EXTENDED = 'extendedMethod';
    
    /**
     * @var  string  the name of the class' private property
     */
    const PROPERTY_PRIVATE = 'basePrivateProperty';
    
    /**
     * @var  string  the name of the class' protected property
     */
    const PROPERTY_PROTECTED = 'baseProtectedProperty';
    
    /**
     * @var  string  the name of the class' public property
     */
    const PROPERTY_PUBLIC = 'basePublicProperty';
    
    /**
     * @var  string  the name of the class' property that'll be extended
     */
    const PROPERTY_EXTENDED = 'extendedProperty';
    
    
    /* !Properties */
    
    private $basePrivateProperty = 'basePrivateProperty';
    
    protected $baseProtectedProperty = 'baseProtectedProperty';
    
    public $basePublicProperty = 'basePublicProperty';
    
    public $extendedProperty = 'baseExtendedProperty';
    
    
    /* !Methods */
    
    private function basePrivateMethod($value)
    {
        return $value;
    }
    
    protected function baseProtectedMethod($value)
    {
        return $value;
    }
    
    public function basePublicMethod($value)
    {
        return $value;
    }
    
    public function extendedMethod($value)
    {
        return $value;
    }
}
