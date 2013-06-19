<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\arrayutils;

trait ArrayGetterTraits
{
  
  //We require this trait in order to function.
  use ArrayAccessTraits;
  
  /**
   * Return the node that is present at given $index.
   * 
   * When `index` is a negative number, the array will be traversed in reverse to find the
   * value, so an `index` of `-1` would return the last node and so forth.
   * When the array is empty, this method returns null.
   *
   * @param int $index The amount of nodes to skip in order to find the desired node.
   *
   * @return mixed The value that was found.
   */
  public function idx($index)
  {
    
    //Reference the array.
    $arr = $this->getArrayReference();
    
    //Can not handle an empty array.
    if(empty($arr)){
      return null;
    }
    
    //Handle negative index.
    if($index < 0) $index = count($arr) + $index;
    if($index < 0) $index = 0;
      
    //Start at 0.
    $i = 0;
    
    //Iterate!
    do
    {
      
      //If we're not there yet, carry on and increase the iteration number.
      if($i < $index){
        $i++;
      }
      
      //If we're there, return the node through offsetGet.
      else{
        return $this->offsetGet(key($arr));
      }
      
    }
    
    //After every iteration, progress the array cursor.
    while($i <= $index && next($arr));    
    
  }
  
  /**
   * Extract a sub-node based on the given array of keys.
   * 
   * If we'd have the following array: `[[['hi']]]`, then to get "hi", we could call
   * `extract([0, 0, 0])`.
   *
   * @param array $keys The keys to look for.
   *
   * @return mixed Whichever value was found. Null if one of the keys didn't exist.
   */
  public function extract(array $keys)
  {
    
    //Set the initial return value.
    $return = $this;
    
    //Get a node from the return value, and set that as the new return value.
    foreach($keys as $key)
    {
      
      //Get a node from an ArrayContainer.
      if($return instanceof ArrayContainerInterface){
        $return = $return->offsetGet($key);
        continue;
      }
      
      //Get node from array.
      if(is_array($return)){
        $return = $return[$key];
        continue;
      }
      
      //Can not handle anything else.
      return null;
      
    }
    
    //Return the latest return value.
    return $return;
      
  }
  
  /**
   * Perform an optionally recursive conversion to a native array.
   *
   * @param boolean $recursive Whether to recursively look for other contained arrays. Defaults to true.
   *
   * @return array
   */
  public function toArray($recursive = true)
  {
    
    //Create the output array.
    $arr = [];
    
    //Iterate.
    foreach($this->getarrayreference() as $key => $value)
    {
      
      //Are we recursively converting?
      if($recursive && $value instanceof ArrayContainerInterface){
        $value = $value->toArray();
      }
      
      //Set the value in the output array.
      $arr[$key] = $value;
      
    }
    
    //Return the output array.
    return $arr;
    
  }
  
  /**
   * Return the node under the given key and remove it from the array.
   *
   * @param mixed $key The key to look for.
   *
   * @return mixed The found value.
   */
  public function steal($key)
  {
    
    //Get the value.
    $value = $this->offsetGet($key);
    
    //Remove from array.
    $this->offsetUnset($key);
    
    //Return the value.
    return $value;
    
  }
  
  /**
   * Return true if the node under the given key is set and resolves to true.
   *
   * @param mixed $key The key to look for.
   *
   * @return boolean
   */
  public function check($key)
  {
    
    return ($this->offsetExists($key) && $this->offsetGet($key));
    
  }
  
}
