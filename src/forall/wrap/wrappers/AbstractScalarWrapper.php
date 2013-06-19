<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap;

abstract class AbstractScalarWrapper extends AbstractWrapper
{
  
  /**
   * Contains the native value that was wrapped.
   * @var mixed
   */
  protected $value;
  
  /**
   * Should validate and set the native value.
   * 
   * @param mixed $value
   */
  abstract public function __construct($value);
  
  /**
   * Returns the native value.
   * 
   * @return mixed
   */
  public function get()
  {
    
    return $this->value;
    
  }
  
  /**
   * Returns true if the value resolves to true.
   *
   * @return boolean
   */
  public function isTrue()
  {
    
    return !!$this->value;
    
  }
  
  /**
   * Returns true if the value resolves to false.
   *
   * @return boolean
   */
  public function isFalse()
  {
    
    return !$this->value;
    
  }
  
  /**
   * Use Successible to check for the result a hasSomething method.
   * 
   * @param string $methodName The method in this object to use.
   */
  public function has($methodName)
  {
    
    //Handle arguments.
    $args = func_get_args();
    
    //Get the method name.
    $method = 'has'.ucfirst(array_shift($args));
    
    //Call the method and use it to set the success state.
    $this->is(!!call_user_func_array([$this, $method], $args));
    
    //Enable chaining.
    return $this;
    
  }
  
  /**
   * Implements "greater than" to determine the success state of Successible.
   *
   * @param  mixed $value A numeric value to check against.
   *
   * @return self Chaining enabled.
   */
  public function gt($value)
  {
    
    //Extract the raw value.
    raw($value);
    
    //Return the result of is() with a great than check.
    return $this->is($this->value > $value);
    
  }
  
  /**
   * Implements "less than" to determine the success state of Successible.
   *
   * @param  mixed $value A numeric value to check against.
   *
   * @return self Chaining enabled.
   */
  public function lt($value)
  {
    
    //Extract the raw value.
    raw($value);
    
    //Return the result of is() with a less than check.
    return $this->is($this->value < $value);
    
  }
  
  /**
   * Implements an equality check to determine the success state of Successible.
   *
   * @param  mixed $value A value to check against.
   *
   * @return self Chaining enabled.
   */
  public function eq($value)
  {
    
    return $this->is($this->value == unwrap($value));
    
  }
  
}
