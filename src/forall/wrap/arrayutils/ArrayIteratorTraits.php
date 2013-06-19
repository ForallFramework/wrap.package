<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\arrayutils;

use \Closure;

trait ArrayIteratorTraits
{
  
  /**
   * Iterate over the array, calling the given Closure for each node.
   *
   * The closure is called with three arguments; the value of the node, the key of the
   * node and the index of the node. If the closure returns false during an iteration,
   * the iteration will be broken.
   *
   * @param Closure $callback
   *
   * @return self Chaining enabled.
   */
  public function each(Closure $callback)
  {
    
    //Start at zero.
    $i = 0;
    
    //Iterate!
    foreach($this->arr as $key => $value)
    {
      
      //Execute the callback and get its return value.
      $r = $callback($value, $key, $i);
      
      //Break if the return value is false.
      if($r === false){
        break;
      }
      
      //Increase the index.
      $i++;
      
    }
    
    //Enable chaining.
    return $this;
    
  }
  
  /**
   * Iterate over the array and return true if it was fully iterated.
   * 
   * Does pretty much the same as `self::each()`, however, the return value of the closure
   * must be true on every iteration, or the iteration will be cancelled and false will
   * be returned.
   *
   * @param Closure $callback
   *
   * @return bool Whether all truth test were passed.
   */
  public function every(Closure $callback)
  {
    
    //Start at zero.
    $i = 0;
    
    //Iterate!
    foreach($this->arr as $key => $value)
    {
      
      //Execute the callback and get its return value.
      $r = $callback($value, $key, $i);
      
      //The return value must be true to continue.
      if($r !== true){
        return false;
      }
      
      //Increase the index.
      $i++;
      
    }
    
    //Return true because all iterations completed.
    return true;
    
  }
  
  /**
   * Iterate the array and it's sub-arrays recursively.
   * 
   * Does the same as each, however, every time an array is encountered in a node, that
   * sub-array will be iterated before progressing to the next node.
   *
   * @param Closure $callback
   *
   * @return [type] [description]
   */
  public function walk(Closure $callback)
  {
    
    //Define the function that will do the recursion for us.
    $walker = function($value, $key, $i) use (&$walker, $callback)
    {
      
      //Extract the array from array containers.
      if($value instanceof ArrayContainerInterface){
        $value = $value->getArrayReference();
        continue;
      }
      
      //Iterate arrays.
      if(is_array($value))
      {
        
        $j=0;
        foreach($value as $key => $val){
          $walker($value, $key, $j);
          $j++;
        }
        
      }
      
      //Execute the callback for non-array nodes.
      $callback($value, $key, $i);
    
    };
    
    //Start by iterating with the walker.
    return $this->each($walker);
    
  }
  
}
