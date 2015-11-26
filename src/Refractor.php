<?php
/**
 * The file for the Refractor class
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2015 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\Refraction;

/**
 * A refractor
 *
 * A refractor is like PHP's native reflector, however, it is related to a specific
 * object instance, not the object's definition.
 *
 * We used a trait so that the *refraction* objects could extend PHP's native 
 * *reflection* objects, but still extend and share the same code.
 *
 * @since  0.1.0
 */
trait Refractor
{
    /* !Protected properties */
    
    /**
     * @var    object  the refractor's instance
     * @since  0.1.0
     */
    protected $instance;
}
