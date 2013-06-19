<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap
{
  
  //Use aaaall the wrappers!
  use \forall\wrap\wrappers\ArrayWrapper;
  use \forall\wrap\wrappers\BooleanWrapper;
  use \forall\wrap\wrappers\FunctionWrapper;
  use \forall\wrap\wrappers\NullWrapper;
  use \forall\wrap\wrappers\NumberWrapper;
  use \forall\wrap\wrappers\ObjectWrapper;
  use \forall\wrap\wrappers\StringWrapper;
  
  /**
   * Wrap given data in its respective data wrapper based on the data type.
   *
   * @param mixed $data The data to be wrapped.
   * 
   * @throws WrapperExcepyion If the given data type could not be matched to a wrapper.
   *
   * @return BaseData Any type of wrapper.
   */
  function wrap($data=null)
  {
    
    //Wrap null.
    if(is_null($data)){
      return new NullWrapper;
    }
    
    //Wrap an array.
    if(is_array($data)){
      return new ArrayWrapper($data);
    }
    
    //Wrap a boolean.
    if(is_bool($data)){
      return new BooleanWrapper($data);
    }
    
    //Wrap a string.
    if(is_string($data)){
      return new StringWrapper($data);
    }
    
    //Wrap a number.
    if(is_numeric($data)){
      return new NumberWrapper($data);
    }
    
    //Wrap a closure.
    if($data instanceof \Closure){
      return new FunctionWrapper($data);
    }
    
    //Wrap an object.
    if(is_object($data)){
      return new ObjectWrapper($data);
    }
    
    //Can not wrap.
    throw new WrapperException(sprintf('No wrapper implemented for data of type: %s', typeof($data)));
    
  }

  /**
   * Does the same as wrap, except does not wrap wrappers in an ObjectWrapper, instead clones them.
   *
   * @param  mixed $input The data to be wrapped.
   * 
   * @see forall\wrap\wrap() for more more information.
   *
   * @return BaseData Any type of wrapper.
   */
  function wrapRaw($input)
  {
    
    //If the input is already a wrapper, return a clone.
    if(is_wrapped($input)){
      return clone $input;
    }
    
    //Wrap and return.
    return wrap($input);
    
  }
  
  /**
   * Returns true if the given data is a wrapper.
   *
   * @param  mixed  $data Any data.
   *
   * @return boolean Whether data was an instance of BaseData.
   */
  function isWrapped($data)
  {
    
    return ($data instanceof BaseData);
    
  }
  
  /**
   * Unwraps given input.
   *
   * @param  mixed $data Any data.
   *
   * @return mixed       The same data, or if it was wrapped, the result of $data->get().
   */
  function unwrap($data)
  {
    
    //Unwrap if wrapped.
    if($data instanceof BaseData){
      return $data->get();
    }
    
    //Return the result.
    return $data;
    
  }
  
  /**
   * Unwraps up to 9 parameters automatically.
   *
   * @param  mixed $v0 One of 9 unwrapping sockets.
   * @param  mixed $v1 One of 9 unwrapping sockets.
   * @param  mixed $v2 One of 9 unwrapping sockets.
   * @param  mixed $v3 One of 9 unwrapping sockets.
   * @param  mixed $v4 One of 9 unwrapping sockets.
   * @param  mixed $v5 One of 9 unwrapping sockets.
   * @param  mixed $v6 One of 9 unwrapping sockets.
   * @param  mixed $v7 One of 9 unwrapping sockets.
   * @param  mixed $v8 One of 9 unwrapping sockets.
   * @param  mixed $v9 One of 9 unwrapping sockets.
   *
   * @return void No return value. The unwrapped values are stored in the given references.
   */
  function raw(
    &$v0=null, &$v1=null, &$v2=null, &$v3=null, &$v4=null,
    &$v5=null, &$v6=null, &$v7=null, &$v8=null, &$v9=null
  ){
    
    //Warn the programmer if some of their things are not going to be unwrapped.
    if(func_num_args() > 10){
      throw new WrapperException('HAHA! You can only extract raw() values from 10 variables at a time.');
    }
    
    //Do the unwrapping.
    $v0 = unwrap($v0);
    $v1 = unwrap($v1);
    $v2 = unwrap($v2);
    $v3 = unwrap($v3);
    $v4 = unwrap($v4);
    $v5 = unwrap($v5);
    $v6 = unwrap($v6);
    $v7 = unwrap($v7);
    $v8 = unwrap($v8);
    $v9 = unwrap($v9);
    
  }
  
}

//Export all functions to the global scope.
namespace
{
  
  //Export the "wrap" function to the global name space.
  if(!function_exists("wrap")){
    function wrap($data){
      return \forall\wrap\wrap($data);
    }
  }
  
  //Export the "wrapRaw" function to the global name space.
  if(!function_exists("wrapRaw")){
    function wrapRaw($data){
      return \forall\wrap\wrapRaw($data);
    }
  }
  
  //Export the "isWrapped" function to the global name space.
  if(!function_exists("isWrapped")){
    function isWrapped($data){
      return \forall\wrap\isWrapped($data);
    }
  }
  
  //Export the "unwrap" function to the global name space.
  if(!function_exists("unwrap")){
    function unwrap($data){
      return \forall\wrap\unwrap($data);
    }
  }
  
  //Export the "raw" function to the global name space.
  if(!function_exists("raw")){
    function raw(
      &$v0=null, &$v1=null, &$v2=null, &$v3=null, &$v4=null,
      &$v5=null, &$v6=null, &$v7=null, &$v8=null, &$v9=null
    ){
      return \forall\wrap\raw($v0, $v1, $v2, $v3, $v4, $v5, $v6, $v7, $v8, $v9);
    }
  }
  
}
