<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\arrayutils;

use \Closure;

trait ArrayMagicTraits
{
  
  //Require the implementation of the "offsetSet"-method.
  abstract public function offsetSet($key, $value);
  
  //Require the implementation of the "offsetExists"-method.
  abstract public function offsetExists($key);
  
  //Require the implementation of the "offsetUnset"-method.
  abstract public function offsetUnset($key);
  
  //Require the implementation of the "offsetGet"-method.
  abstract public function offsetGet($key);
  
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
