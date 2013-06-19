<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\wrappers;

use forall\wrap\successible\SuccessibleInterface;
use forall\wrap\successible\SuccessibleTraits;

abstract class AbstractWrapper implements SuccessibleInterface
{
  
  //Use the successible trait, and implement some of its methods as private members, so we can extend them.
  use SuccessibleTraits
  {
    is as private _is;
    not as private _not;
    success as private _success;
    failure as private _failure;
  }
  
  /**
   * Should return the native data.
   *
   * @return mixed The native data wrapped by the wrapper.
   */
  abstract public function get();
  
  /**
   * Should return the native data cast to a string.
   *
   * @return StringWrapper
   */
  abstract public function toString();
  
  /**
   * Should return the native data converted to JSON.
   *
   * @return StringWrapper Wrapped JSON data.
   */
  abstract public function toJSON();
  
  /**
   * Should return a representation of what the native data is as string.
   *
   * @return StringWrapper Visual representation of the native data.
   */
  abstract public function visualize();
  
  /**
   * Alias for self::toString. Used by PHP magic.
   *
   * @see self::toString() for implementation.
   *
   * @return string
   */
  final public function __toString()
  {
    
    return $this->toString()->get();
    
  }
  
  /**
   * An alias for self::get().
   * 
   * @see self::get() for implementation.
   *
   * @return mixed
   */
  final public function unwrap()
  {
    
    return $this->get();
    
  }
  
  /**
   * Assign this instance to the given reference.
   *
   * @param  mixed $reference Whatever is passed here will be overridden, so it better not be important.
   * 
   * @see self::wrapRaw() for a way to assign the native value to the given reference.
   *
   * @return self Chaining enabled.
   */
  public function put(&$reference)
  {
    
    //Do it.
    $reference = $this;
    
    //Enable chaining.
    return $this;
    
  }
  
  /**
   * Assign the native data to the given reference.
   *
   * @param  mixed $reference Whatever is passed here will be overridden, so it better not be important.
   * 
   * @see self::put() for a way to assign this object to the given reference.
   *
   * @return self Chaining enabled.
   */
  public function putRaw(&$reference)
  {
    
    //Do it.
    $reference = $this->get();
    
    //Enable chaining.
    return $this;
    
  }
  
  /**
   * Clone this object.
   *
   * @return self A clone.
   */
  public function copy()
  {
    
    return clone $this;
    
  }
  
  /**
   * Returns true if this object does not wrap null.
   *
   * @return boolean
   */
  public function isDefined()
  {
    
    return !($this instanceof NullWrapper);
    
  }
  
  /**
   * Returns true if this object wraps a scalar data type.
   *
   * @return boolean
   */
  public function isScalar()
  {
    
    return ($this instanceof AbstractScalarWrapper);
    
  }
  
  /**
   * Returns true if this object wraps numeric data.
   *
   * @return boolean
   */
  public function isNumeric()
  {
    
    return is_numeric($this->get());
    
  }
  
  /**
   * Returns true if this object wraps an array.
   *
   * @return boolean
   */
  public function isArray()
  {
    
    return ($this instanceof ArrayWrapper);
    
  }
  
  /**
   * Return true if this objects wraps a string.
   *
   * @return boolean
   */
  public function isString()
  {
    
    return ($this instanceof StringWrapper);
    
  }
  
  /**
   * Return true if this object wraps a number.
   *
   * @return boolean
   */
  public function isNumber()
  {
    
    return ($this instanceof NumberWrapper);
    
  }
  
  /**
   * Return true if this object wraps a boolean.
   *
   * @return boolean
   */
  public function isBool()
  {
    
    return ($this instanceof BoolWrapper);
    
  }
  
  /**
   * Return true if this object wraps a Closure.
   *
   * @return boolean
   */
  public function isFunction()
  {
    
    return ($this instanceof FunctionWrapper);
    
  }
  
  /**
   * Return true if this objects wraps an object.
   *
   * @return boolean
   */
  public function isObject()
  {
    
    return ($this instanceof ObjectWrapper);
    
  }
  
  /**
   * Return the type of the native value.
   *
   * @return string A data-type.
   */
  public function type()
  {
    
    return gettype($this->value);
    
  }
  
  /**
   * Extend the Successible is() method.
   * 
   * @see SuccessibleInterface::is() for primary functionality.
   * 
   * This extension adds the possibility to pass a string. When done, it is used to call
   * any is"Something" method on this object to determine the new success state.
   *
   * @param  mixed $check
   *
   * @return self Chaining enabled.
   */
  public function is($check)
  {
    
    //No string given? Do the old stuff.
    if(!is_string($check)){
      return $this->_is($check);
    }
    
    //Uppercase the first letter of the given check.
    $check = ucfirst($check);
    
    //Do the old stuff using the boolean returned by the given check method.
    return $this->_is($this->{"is$check"}());
    
  }
  
  /**
   * Extend the Successible not() method.
   *
   * @see SuccessibleInterface::is() for primary functionality.
   * @see self::is() for extension.
   *
   * @param  mixed $check
   *
   * @return self Chaining enabled.
   */
  public function not($check)
  {
    
    //No string given? Do the old stuff.
    if(!is_string($check)){
      return $this->_not($check);
    }
    
    //Uppercase the first letter of the given check.
    $check = ucfirst($check);
    
    //Do the old stuff using the boolean returned by the given check method.
    return $this->_not($this->{"is$check"}());
  
  }
  
  /**
   * Extend the Successible success method.
   * 
   * This simply causes anything returned by the success method to be automatically wrapped.
   * 
   * @see SuccessibleInterface::success() for documentation.
   *
   * @param  callable $callback
   *
   * @return self Wrapped data.
   */
  public function success(callable $callback = null)
  {
    
    return wrapRaw($this->_success($callback));
    
  }
  
  /**
   * @see SuccessibleInterface::success() for documentation.
   * 
   * This is here to implement the functionality that the success method has when called
   * without a parameter.
   *
   * @return boolean The success state of this object.
   */
  public function isSuccess()
  {
    
    return $this->_success();
    
  }
  
  /**
   * Extend the Successible failure method.
   * 
   * This simply causes anything returned by the failure method to be automatically wrapped.
   * 
   * @see SuccessibleInterface::failure() for documentation.
   *
   * @param  callable $callback
   *
   * @return self Wrapped data.
   */
  public function failure(callable $callback = null)
  {
    
    return wrapRaw($this->_failure($callback));
    
  }
  
  /**
   * @see SuccessibleInterface::failure() for documentation.
   * 
   * This is here to implement the functionality that the failure method has when called
   * without a parameter.
   *
   * @return boolean The inverse success state of this object.
   */
  public function isFailure()
  {
    
    return $this->_failure();
    
  }
  
}
