<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\arrayutils;

use \Closure;

trait ArrayMagicTraits
{
  
  //We need access to the array to perform our magic.
  use ArrayAccessTraits;
  
  /**
   * Alias for self::offsetSet().
   *
   * @param mixed $key
   * @param mixed $value
   */
  public function __set($key, $value)
  {
    
    return $this->offsetSet($key, $value);
    
  }
  
  /**
   * Alias for self::offsetGet().
   *
   * @param mixed $key
   *
   * @return mixed
   */
  public function __get($key)
  {
    
    return $this->offsetGet($key);
    
  }
  
  /**
   * Alias for self::offsetUnset().
   *
   * @param mixed $key
   */
  public function __unset($key)
  {
    
    return $this->offsetUnset($key);
    
  }
  
  /**
   * Alias for self::offsetExists().
   *
   * @param mixed $key
   *
   * @return boolean
   */
  public function __isset($key)
  {
    
    return $this->offsetExists($key);
    
  }
  
}
