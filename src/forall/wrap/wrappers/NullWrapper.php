<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\wrappers;

use forall\wrap\WrapException;

class NullWrapper extends AbstractWrapper
{
  
  /**
   * Return NULL.
   *
   * @return null
   */
  public function get()
  {
    
    return null;
    
  }
  
  /**
   * Return the name of this class.
   *
   * @return StringWrapper
   */
  public function toString()
  {
    
    return new StringWrapper('['.__CLASS__.']');
    
  }
  
  /**
   * Return null.
   *
   * @return StringWrapper
   */
  public function toJSON()
  {
    
    return new StringWrapper('null');
    
  }
  
  /**
   * Return "NULL".
   *
   * @return StringWrapper
   */
  public function visualize()
  {
    
    return new StringWrapper('"NULL"');
    
  }
  
  /**
   * Can not call methods on NullWrapper.
   *
   * @param mixed $key Dummy.
   * @param mixed $args Dummy.
   *
   * @throws WrapException If this method gets called.
   *
   * @return void
   */
  public function __call($key, $args)
  {
    
    throw new WrapException(sprintf('Can not call method "%s" of NullWrapper.', $key));
    
  }
  
  /**
   * Can not get nodes of NullWrapper.
   *
   * @param mixed $key Dummy.
   * 
   * @throws WrapException If this method gets called.
   *
   * @return void
   */
  public function __get($key)
  {
    
    throw new WrapException(sprintf('Can not get "%s" of NullWrapper.', $key));
    
  }
  
  /**
   * Can not set nodes of NullWrapper.
   *
   * @param mixed $key Dummy.
   * @param mixed $value Dummy.
   * 
   * @throws WrapException If this method gets called.
   * 
   * @return void
   */
  public function __set($key, $value)
  {
    
    throw new WrapException(sprintf('Can not set "%s" of NullWrapper.', $key));
    
  }
  
  /**
   * Return the wrapped value.
   *
   * @param mixed $value Anything.
   *
   * @return AbstractWrapper Wrapping whatever was given as argument.
   */
  public function alt($value)
  {
    
    return wrap($value);
    
  }
  
  /**
   * NullWrapper is always empty.
   *
   * @return true
   */
  public function isEmpty()
  {
    
    return true;
    
  }
  
}
