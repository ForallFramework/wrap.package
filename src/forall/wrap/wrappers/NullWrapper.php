<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\wrappers;

class NullWrapper extends BaseData
{
  
  //Return NULL.
  public function get()
  {
    
    return null;
    
  }
  
  //Return an appropriate indication of fail.
  public function toString()
  {
    
    return new StringWrapper('[data\\NullWrapper]');
    
  }
  
  //Return null.
  public function toJSON()
  {
    
    return new StringWrapper('null');
    
  }
  
  //Return "NULL".
  public function visualize()
  {
    
    return new StringWrapper("NULL");
    
  }
  
  //Can not call methods on NullWrapper.
  public function __call($key, $args)
  {
    
    throw new \exception\Restriction('Can not call method "%s" of NullWrapper.', $key);
    
  }
  
  //Can not get nodes of NullWrapper.
  public function __get($key)
  {
    
    throw new \exception\Restriction('Can not get "%s" of NullWrapper.', $key);
    
  }
  
  //Can not set nodes of NullWrapper.
  public function __set($key, $value)
  {
    
    throw new \exception\Restriction('Can not set "%s" of NullWrapper.', $key);
    
  }
  
  //Return the wrapped value.
  public function alt($value)
  {
    
    return wrap($value);
    
  }
  
  //NullWrapper is always empty.
  public function isEmpty()
  {
    
    return true;
    
  }
  
}
