<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\successible;

use SuccessibleInterface;

trait SuccessibleTraits
{
  
  protected $success=null;
  
  /**
   * @see SuccessibleInterface::is() for implementation.
   */
  public function is($check)
  {
    
    //Set the success state.
    $this->success = $this->_doCheck($check);
    
    //Enable chaining.
    return $this;
    
  }
  
  /**
   * @see SuccessibleInterface::not() for implementation.
   */
  public function not($check)
  {
    
    //Set the inverse success state.
    $this->success = !$this->_doCheck($check);
    
    //Enable chaining.
    return $this;
    
  }
  
  /**
   * @see SuccessibleInterface::andIs() for implementation.
   */
  public function andIs($check)
  {
    
    //Only proceed if we're already successful.
    if($this->success === false){
      return $this;
    }
    
    //Call self::is.
    return $this->is($check);
    
  }
  
  /**
   * @see SuccessibleInterface::andNot() for implementation.
   */
  public function andNot($check)
  {
    
    //Only proceed if we're currently successful.
    if($this->success === false){
      return $this;
    }
    
    //Call self::not.
    return $this->not($check);
    
  }
  
  /**
   * @see SuccessibleInterface::success() for implementation.
   */
  public function success(callable $callback=null)
  {
    
    //No callback given? Return the success state.
    if(is_null($callback)){
      return $this->success;
    }
    
    //Do the callback if we're currently successful.
    if($this->success === true){
      return $this->_doCallback($callback);
    }
    
    //Enable chaining.
    return $this;
    
  }
  
  /**
   * @see SuccessibleInterface::failure() for implementation.
   */
  public function failure(callable $callback=null)
  {
    
    //No callback given? Return the inverse success state.
    if(is_null($callback)){
      return !$this->success;
    }
    
    //Do the callback if we're currently unsuccessful.
    if($this->success === false){
      return $this->_doCallback($callback);
    }
    
    //Enable chaining.
    return $this;
    
  }
  
  /**
   * Executes a callback and returns its return value if it's a SuccessibleInterface. Returns $this otherwise.
   *
   * @param  callable $callback The callback to execute.
   *
   * @return SuccessibleInterface Either $this or the return value of the callback.
   */
  private function _doCallback(callable $callback)
  {
    
    //Get the return value.
    $return = $callback($this);
    
    //Return this or the value.
    return (($return instanceof SuccessibleInterface) ? $return : $this);
    
  }
  
  /**
   * Convert given $check to boolean.
   * 
   * Converts the given $check to a boolean by following the rules described in
   * SuccessibleInterface::is.
   * 
   * @see SuccessibleInterface::is() for the rules.
   *
   * @param  mixed $check Any value matching the rules.
   *
   * @return boolean
   */
  private function _doCheck($check)
  {
    
    //Use the return value of a closure?
    if($check instanceof \Closure){
      return (bool) $check($this);
    }
    
    //Use the success state of another Successible object?
    if($check instanceof SuccessibleInterface){
      return $check->success();
    }
    
    //Simply cast to boolean.
    return (bool) $check;
    
  }
  
}

