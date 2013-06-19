<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\arrayutils;

trait ArrayInformationTraits
{
  
  /**
   * Return the size of the array.
   * 
   * That is, the amount of nodes it has.
   *
   * @return integer
   */
  public function size()
  {
    
    return count($this->getArrayReference());
    
  }
  
  /**
   * Returns true if one of the nodes in the array has the given value.
   *
   * @param mixed $value The value to find.
   * @param boolean $strict Whether to find it using strict matching.
   *
   * @return boolean
   */
  public function has($value, $strict=false)
  {
    
    return $this->keyAt($value, $strict) !== false;
    
  }
  
  /**
   * Returns true if the array is empty.
   *
   * @return boolean
   */
  public function isEmpty()
  {
    
    //Get an actual copy of the array, or the empty construct will freak out.
    $arr = $this->getArrayReference();
    
    //Do the check and return.
    return empty($arr);
    
  }
  
  /**
   * Returns the key of the first element in this array with the given value.
   *
   * @param mixed $value The value to look for
   * @param boolean $strict Whether to find it using strict matching.
   *
   * @return mixed The key found or false.
   */
  public function keyAt($value, $strict=false)
  {
    
    return array_search($value, $this->getArrayReference(), $strict);
    
  }
    
}
