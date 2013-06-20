<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\wrappers;

use \ReflectionObject;

/**
 * @method {StringWrapper} class() class() {@see ObjectWrapper::_public_class()}
 */
class ObjectWrapper extends AbstractWrapper
{
  
  /**
   * The wrapped object.
   * @var object
   */
  private $object;
  
  /**
   * Validate and set the value.
   *
   * @param object $value
   * 
   * @throws WrapException If the given value is no object.
   */
  public function __construct($value)
  {
    
    //Validate.
    if(!(is_object($value))){
      throw new WrapException(sprintf('Expecting $value to be an object. %s given.', gettype($value)));
    }
    
    //Set.
    $this->object = $value;
    
  }
  
  /**
   * Return the object.
   *
   * @return object
   */
  public function get()
  {
    
    return $this->object;
    
  }
  
  /**
   * Implement some magic to bypass reserved words.
   * 
   * If any non-existent method is called, this method will try to forward to a private
   * method of the same name with "_public_" prefixed to it.
   * 
   * @param string $method The method to call.
   * @param array $arguments The arguments to pass.
   *
   * @return mixed Whatever was returned by the matched method.
   */
  public function __call($method, $arguments)
  {
    
    return call_user_func_array([$this, "_public_$method"], $arguments);
    
  }
  
  /**
   * Cast the object to string.
   *
   * @return StringWrapper
   */
  public function toString()
  {
    
    return new StringWrapper('['.__CLASS__.']');
    
  }
  
  /**
   * Return the variables of the wrapped object in JSON format.
   *
   * @return StringWrapper
   */
  public function toJSON()
  {
    
    return new StringWrapper(json_encode($this->vars()));
    
  }
  
  /**
   * Return a StringWraper containing the visual representation of this object.
   *
   * @return StringWrapper
   */
  public function visualize()
  {
    
    return new StringWrapper('object('.$this->name().')');
    
  }
  
  /**
   * Get the variables in the object as a wrapped array.
   *
   * @return ArrayWrapper
   */
  public function vars()
  {
    
    return new ArrayWrapper(get_object_vars($this->object));
    
  }
  
  /**
   * Returns a wrapped string containing the class name of the object.
   *
   * This method is private, but publicly accessible through calling `$this->class()`.
   *
   * @return StringWrapper
   */
  private function _public_class()
  {
    
    return new StringWrapper(get_class($this->object));
    
  }
  
  /**
   * Returns a wrapped string containing the class name of the object without namespace.
   *
   * @return StringWrapper
   */
  public function baseclass()
  {
    
    return new StringWrapper(substr_count($this->_public_class()->get(), '\\') > 0
      ? substr(strrchr($this->_public_class()->get(), '\\'), 1)
      : $this->_public_class()->get()
    );
    
  }
  
  /**
   * Return a Reflection instance of this object.
   *
   * @return ReflectionObject
   */
  public function getReflector()
  {
    
    return new ReflectionObject($this->object);
    
  }
  
  /**
   * Returns true if this objects class uses the given trait.
   *
   * @param string $trait_name
   *
   * @return BooleanWrapper
   */
  public function uses($trait_name)
  {
    
    //Start with the object.
    $object = $this->object;
    
    //Get the traits used by its class.
    do{
      if(array_key_exists("traits\\$trait_name", class_uses($object))){
        return new BooleanWrapper(true);
      }
    }
    
    //Move to the parent class.
    while($object = get_parent_class($object));
    
    //Nope.
    return new BooleanWrapper(false);
    
  }
  
  /**
   * Get this objects unique ID.
   *
   * @return NumberWrapper
   */
  function id()
  {
    
    //Object ID pool.
    static $object_ids = [];
    
    //Get the object hash.
    $hash = spl_object_hash($this->object);
    
    //Find the object ID in out pool?
    if(array_key_exists($hash, $object_ids)){
      $id = $object_ids[$hash];
    }
    
    //Create the object ID.
    else{
      $object_ids[$hash] = $id = (count($object_ids) + 1);
    }
    
    //Return the object ID.
    return new NumberWrapper($id);

  }
  
  /**
   * Return the unique name of this object.
   * 
   * Consisting of class name + object ID.
   * 
   * @see self::id()
   * @see self::_public_class()
   *
   * @return StringWrapper
   */
  function name()
  {
    
    return new StringWrapper(get_class($this->object).'#'.$this->id());
    
  }
  
}
