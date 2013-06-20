<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\wrappers;

use Closure;

class FunctionWrapper extends AbstractScalarWrapper
{
  
  /**
   * Validate and set the value.
   * 
   * @throws WrapException If the given value was no closure.
   *
   * @param Closure $value
   */
  public function __construct($value)
  {
    
    //Validate.
    if(!($value instanceof Closure)){
      throw new WrapException(sprintf('Expecting $value to be a Closure. %s given.', gettype($value)));
    }
    
    //Set.
    $this->value = $value;
    
  }
  
  /**
   * Cast the boolean to string.
   *
   * @return StringWrapper
   */
  public function toString()
  {
    
    return new StringWrapper('[data\Function]');
    
  }
  
  /**
   * Return null. Functions can not properly be converted to JSON.
   *
   * @return StringWrapper A StringWrapper containing "null".
   */
  public function toJSON()
  {
    
    return new StringWrapper('null');
    
  }
  
  /**
   * Return {Closure}.
   *
   * @return StringWrapper A StringWrapper containing "{Closure}".
   */
  public function visualize()
  {
    
    return new StringWrapper('{Closure}');
    
  }
  
  /**
   * Return a new FunctionWrapper, bound to a new object.
   *
   * @param object $object The object to bind to.
   *
   * @return FunctionWrapper Wrapping a closure which is bound to the given object.
   */
  public function bind($object)
  {
    
    return new self($this->value->bindTo($object));
    
  }
  
  /**
   * Call the function, passing on the given arguments and return the return value.
   * 
   * @param mixed $parameter The first optional parameter to pass.
   * @param mixed ... The above parameter repeats indefinitely.
   *
   * @return AbstractWrapper The return value of the function, wrapped.
   */
  public function call()
  {
    
    return wrap(call_user_func_array($this->value, func_get_args()));
    
  }
  
  /**
   * Call the function, using the given array of arguments and return the return value.
   *
   * @param array $args An array with any number of arguments.
   *
   * @return AbstractWrapper The return value of the function, wrapped.
   */
  public function apply(array $args)
  {
    
    return wrap(call_user_func_array($this->value, $args));
    
  }
  
  /**
   * Returns the function wrapped by the given wrapper Closure.
   * 
   * Returns a new FunctionWrapper containing a closure that, when called, executes the
   * given $wrapper with the currently wrapped Closure as first argument. For example:
   * 
   * ```php
   * (new FunctionWrapper(function($foo){echo $foo}))
   * 
   * ->wrap(function($old, $param1){
   *   //$old Contains the previously defined Closure.
   *   $old($param1);
   * })
   * 
   * ->call("Hello World");
   * ```
   *
   * @param Closure $wrapper This will be the closure that gets called internally with a
   *                         variable amount of arguments: The wrapped closure, any number
   *                         of arguments passed to the wrapping closure.
   *
   * @return FunctionWrapper Wrapping the generated Closure.
   */
  public function wrap(Closure $wrapper)
  {
    
    //Reference the value.
    $value =& $this->value;
    
    //Generate the wrapper.
    return new self(function()use(&$value, $wrapper){
      return call_user_func_array($wrapper, array_merge([$value], func_get_args()));
    });
    
  }
  
}
