<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\arrayutils;

trait ArraySetterTraits
{
  
  //Require the implementation of the "& getArrayReference"-method.
  abstract public function & getArrayReference();
  
  //Require the implementation of the "offsetSet"-method.
  abstract public function offsetSet($key, $value);
  
  //Require the implementation of the "offsetExists"-method.
  abstract public function offsetExists($key);
  
  //Require the implementation of the "offsetUnset"-method.
  abstract public function offsetUnset($key);
  
  /**
   * Merge one or more given arrays with the contained array.
   *
   * @param array $merge The first optional array to merge.
   * @param array ... The previous parameter repeats indefinitely.
   *
   * @return self Chaining enabled.
   */
  public function merge()
  {
    
    //Iterate arguments.
    foreach(func_get_args() as $array)
    {
      
      //Cast ArrayContainers to arrays.
      if($array instanceof ArrayContainerInterface){
        $array = $array->getArrayReference();
      }
      
      //Iterate the given array, using its key and value for ourselves.
      foreach($array as $key => $value){
        $this->offsetSet($key, $value);
      }
      
    }
    
    //Enable chaining.
    return $this;
    
  }
  
  /**
   * Adds one or more given arrays tot this array.
   * 
   * Does the same as `self::merge`, except it renames keys that are already in use, so
   * that all values will be preserved.
   * 
   * @see self::merge() for adding arrays without renaming the keys.
   * 
   * @param array $concat The first optional array to be concatenated.
   * @param array ... The previous parameter repeats indefinitely.
   *
   * @return self Chaining enabled.
   */
  public function concat()
  {
    
    //Get the used keys.
    $used = array_keys($this->arr);
    $used_numeric = array_filter($used, function($v){return is_numeric($v);});
    
    //Get the currently highest key.
    $i = ((count($used_numeric) > 0) ? (max($used_numeric) + 1) : 0);
    
    //Iterate arguments.
    foreach(func_get_args() as $array)
    {
      
      //Cast ArrayContainers to arrays.
      if($array instanceof ArrayContainerInterface){
        $array = $array->getArrayReference();
      }
      
      //Iterate the array.
      foreach($array as $key => $value)
      {
        
        //Check if the key is already used, if it is, generate a new key.
        if(in_array($key, $used)){
          $used[] = $key = $i++;
        }
        
        //Set the value.
        $this->offsetSet($key, $value);
        
      }
      
    }
    
    //Enable chaining.
    return $this;
    
  }
  
  /**
   * Set default values in the array.
   * 
   * Like merge, but only sets values that are not set the this array yet.
   * 
   * @param array $defaults The first optional array to use for defaults.
   * @param array ... The previous parameter repeats indefinitely.
   *
   * @return self Chaining enabled.
   */
  public function defaults()
  {
    
    //Iterate arguments.
    foreach(func_get_args() as $array)
    {
      
      //Cast ArrayContainers to arrays.
      if($array instanceof ArrayContainerInterface){
        $array = $array->getArrayReference();
      }
      
      //Iterate the given array and use the value as our own, if the key is not yet in use.
      foreach($array as $key => $value){
        if(!$this->offsetExists($key)){
          $this->offsetSet($key, $value);
        }
      }
      
    }
    
    //Enable chaining.
    return $this;
        
  }
  
  /**
   * Push a new value into the array with an optional key.
   *
   * @param mixed $key The key to register the value under. This parameter can be left out.
   * @param mixed $value The value to push into the array.
   *
   * @return self Chaining enabled.
   */
  public function push()
  {
    
    //Handle arguments.
    $args = func_get_args();
    
    //Get the value and key.
    $value = array_pop($args);
    $key = (empty($args) ? null : array_pop($args));
    
    //Calculate a key if none was given.
    if(is_null($key))
    {
      
      //Get all the numeric array keys.
      $keys = array_filter(array_keys($this->arr), function($var){
        return is_numeric($var);
      });
      
      //Make a new key based on that.
      $key = (empty($keys) ? 0 : max($keys)+1);
      
    }
    
    //Set.
    return $this->offsetSet($key, $value);
    
  }
  
  /**
   * Unset nodes of the given key(s) from the contained array.
   *
   * @param array $keys A list of keys to remove from the array.
   *
   * @return self Chaining enabled.
   */
  public function remove(array $keys)
  {
    
    //Iterate the given keys and unset them from the array.
    foreach($keys as $key){
      $this->offsetUnset($key);
    }
    
    //Enable chaining.
    return $this;
    
  }
  
}
