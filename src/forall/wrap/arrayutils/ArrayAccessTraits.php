<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\arrayutils;

trait ArrayAccessTraits
{
  
  /**
   * Return a node from the contained array.
   *
   * @param  mixed $key The key to look for.
   *
   * @return mixed The value found.
   */
  public function offsetGet($key)
  {
    
    return $this->getArrayReference()[$key];
    
  }
  
  /**
   * Set a specific node to a new value within the contained array.
   *
   * @param  mixed $key The key to look for.
   * @param  mixed $value The value to set it to.
   *
   * @return self Chaining enabled.
   */
  public function offsetSet($key, $value)
  {
    
    //Set the value.
    $this->getArrayReference()[$key] = $value;
    
    //Enable chaining.
    return $this;
    
  }
  
  /**
   * Return true if the given exists within the array.
   *
   * @param [type] $key [description]
   *
   * @return [type] [description]
   */
  public function offsetExists($key)
  {
    
    return array_key_exists($key, $this->getArrayReference());
    
  }
  
  /**
   * Unset a specific node from the contained array.
   *
   * @param mixed $key The key to look for.
   *
   * @return self Chaining enabled.
   */
  public function offsetUnset($key)
  {
    
    //Unset from the array.
    unset($this->getArrayReference()[$key]);
    
    //Enable chaining.
    return $this;
    
  }
  
}
