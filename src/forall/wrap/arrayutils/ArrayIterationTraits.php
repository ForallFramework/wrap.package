<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\arrayutils;

trait ArrayIterationTraits
{
  
  /**
   * Return the value of the node under the cursor.
   *
   * @return mixed
   */
  public function current()
  {
    
    return current($this->getArrayReference());
    
  }
  
  /**
   * Return the key of the node under the cursor.
   *
   * @return mixed
   */
  public function key()
  {
    
    return key($this->getArrayReference());
    
  }
  
  /**
   * Advance the cursor and return the value of that node.
   *
   * @return mixed
   */
  public function next()
  {
    
    return next($this->getArrayReference());
    
  }
  
  /**
   * Move the cursor back one node and return the value of it.
   *
   * @return mixed
   */
  public function prev()
  {
    
    return prev($this->getArrayReference());
    
  }
  
  /**
   * Reset the cursor to the first node and return the value.
   *
   * @return mixed
   */
  public function reset()
  {
    
    return reset($this->getArrayReference());
    
  }
  
  /**
   * Move the cursor to the last node and return the value.
   *
   * @return mixed
   */
  public function end()
  {
    
    return end($this->getArrayReference());
    
  }
  
  /**
   * Return an array with 2 nodes. The key and the value. Advances the cursor.
   *
   * @return array
   */
  public function pair()
  {
    
    return each($this->getArrayReference());
    
  }
  
}
