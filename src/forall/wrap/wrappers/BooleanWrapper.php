<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\wrappers;

use forall\wrap\WrapException;

class BooleanWrapper extends AbstractScalarWrapper
{
  
  /**
   * Validate and set the boolean.
   *
   * @throws WrapException If the value given was no boolean.
   *
   * @param boolean $value
   */
  public function __construct($value)
  {
    
    //Validate.
    if(!(is_bool($value))){
      throw new WrapException(sprintf('Expecting $value to be a boolean. %s given.', gettype($value)));
    }
    
    //Set.
    $this->value = $value;
    
  }
  
  /**
   * Cast the boolean to string.
   *
   * @return StringWrapper The wrapped string.
   */
  public function toString()
  {
    
    return new StringWrapper((string) $this->value);
    
  }
  
  /**
   * Return a StringWrapper containing the boolean in JSON format.
   *
   * @return StringWrapper
   */
  public function toJSON()
  {
    
    return $this->visualize();
    
  }
  
  /**
   * Return a StringWrapper containing the visual representation of this boolean.
   *
   * @return StringWrapper
   */
  public function visualize()
  {
    
    return new StringWrapper($this->isTrue() ? 'true' : 'false');
    
  }
  
  /**
   * Return the wrapped alternative if this boolean is false.
   *
   * @param mixed $alternative Anything.
   *
   * @return AbstractWrapper $this Or the wrapped alternative.
   */
  public function alt($alternative)
  {
    
    return ($this->isTrue() ? $this : wrap($alternative));
    
  }
  
}
