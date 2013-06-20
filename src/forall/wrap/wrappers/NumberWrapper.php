<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\wrappers;

use forall\wrap\WrapException;

class NumberWrapper extends AbstractScalarWrapper
{
  
  /**
   * Validate and set the value.
   *
   * @param int|double $value The number to set.
   * 
   * @throws WrapException If the given value was not a number.
   */
  public function __construct($value)
  {
    
    //Validate.
    if(!(is_int($value) || is_float($value) || is_real($value) || is_long($value))){
      throw new WrapException(sprintf('Expecting $value to be a number. %s given.', gettype($value)));
    }
    
    //Set.
    $this->value = $value;
    
  }
  
  /**
   * Cast the number to string.
   *
   * @return StringWrapper
   */
  public function toString()
  {
    
    return new StringWrapper((string) $this->value);
    
  }
  
  /**
   * Return a StringWrapper containing the number in JSON format.
   *
   * @return StringWrapper
   */
  public function toJSON()
  {
    
    return $this->toString();
    
  }
  
  /**
   * Return a StringWrapper containing the visual representation of this number.
   *
   * @return StringWrapper
   */
  public function visualize()
  {
    
    return $this->toString();
    
  }
  
  /**
   * Return the wrapped alternative if this number is zero or lower.
   *
   * @param mixed $alternative Anything.
   *
   * @return AbstractWrapper $this Or the wrapped alternative.
   */
  public function alt($alternative)
  {
    
    return (($this->value > 0) ? $this : wrap($alternative));
    
  }
  
  
  ##
  ## MATH
  ##
  
  /**
   * Returns the absolute value of this number.
   *
   * @return self
   */
  public function abs()
  {
    
    return new self(abs($this->value));
    
  }
  
  /**
   * Returns the arc-cosine of this number.
   *
   * @return self
   */
  public function acos()
  {
    
    return new self(acos($this->value));
    
  }
  
  /**
   * Returns the inverse hyperbolic cosine of this number.
   *
   * @return self
   */
  public function acosh()
  {
    
    return new self(acosh($this->value));
    
  }
  
  /**
   * Returns the arcsine of this number.
   *
   * @return self
   */
  public function asin()
  {
    
    return new self(asin($this->value));
    
  }
  
  /**
   * Returns the inverse hyperbolic sine of this number.
   *
   * @return self
   */
  public function asinh()
  {
    
    return new self(asinh($this->value));
    
  }
  
  /**
   * Returns the arctangent of this number as a numeric value between -PI/2 and PI/2 radians.
   *
   * @return self
   */
  public function atan()
  {
    
    return new self(atan($this->value));
    
  }
  
  /**
   * Returns the inverse hyperbolic tangent of this number.
   *
   * @return self
   */
  public function atanh()
  {
    
    return new self(atanh($this->value));
    
  }
  
  /**
   * Returns the value of this number rounded upwards to the nearest integer.
   *
   * @return self
   */
  public function ceil()
  {
    
    return new self(ceil($this->value));
    
  }
  
  /**
   * Returns the cosine of this number.
   *
   * @return self
   */
  public function cos()
  {
    
    return new self(cos($this->value));
    
  }
  
  /**
   * Returns the hyperbolic cosine of this number.
   *
   * @return self
   */
  public function cosh()
  {
    
    return new self(cosh($this->value));
    
  }
  
  /**
   * Divide the wrapped number by the given number.
   *
   * @param int|double|string $n Numeric value.
   *
   * @return self
   */
  public function divide($n)
  {
    
    return new self($this->value / $n);
    
  }
  
  /**
   * Returns the value of Ex.
   *
   * @return self
   */
  public function exp()
  {
    
    return new self(exp($this->value));
    
  }
  
  /**
   * Returns the value of Ex - 1.
   *
   * @return self
   */
  public function expm1()
  {
    
    return new self(expm1($this->value));
    
  }
  
  /**
   * Returns the value of this number rounded downwards to the nearest integer.
   *
   * @return self
   */
  public function floor()
  {
    
    return new self(floor($this->value));
    
  }
  
  /**
   * Returns the value of this number to the power of n.
   *
   * @param int|double|string $n Numeric value.
   *
   * @return self
   */
  public function pow($n)
  {
    
    return new self(pow($this->value, $n));
    
  }
  
  /**
   * Converts this number from one base to another, returns the result as string.
   *
   * @param int $from The base to use as starting point.
   * @param int $to The base to convert to.
   *
   * @return StringWrapper
   */
  public function rebase($from, $to)
  {
    
    return new StringWrapper((string) base_convert($this->value, $from, $to));
    
  }
  
  /**
   * Rounds this number to the nearest integer.
   *
   * @return self
   */
  public function round()
  {
    
    return new self(round($this->value));
    
  }
  
  /**
   * Returns the sine of this number.
   *
   * @return self
   */
  public function sin()
  {
    
    return new self(sin($this->value));
    
  }
  
  /**
   * Returns the hyperbolic sine of this number.
   *
   * @return self
   */
  public function sinh()
  {
    
    return new self(sinh($this->value));
    
  }
  
  /**
   * Returns the square root of this number.
   *
   * @return self
   */
  public function sqrt()
  {
    
    return new self(sqrt($this->value));
    
  }
  
  /**
   * Returns the tangent of an angle.
   *
   * @return self
   */
  public function tan()
  {
    
    return new self(tan($this->value));
    
  }
  
  /**
   * Multiply the wrapped number by the given number.
   *
   * @param int|double|string $n Numeric value.
   *
   * @return self
   */
  public function times($n)
  {
    
    return new self($this->value * $n);
    
  }
  
  /**
   * Returns the hyperbolic tangent of an angle.
   *
   * @return self
   */
  public function tanh()
  {
    
    return new self(tanh($this->value));
    
  }
  
  
  ##
  ## BITS
  ##
  
  /**
   * Check if a bitwise haystack contains the needle bit.
   *
   * @param int $needle The bits to check.
   *
   * @return boolean Whether the wrapped number contained the given bits.
   */
  public function hasBit($needle)
  {
    
    return (($this->value & $needle) === $needle);
    
  }
  
  /**
   * Returns the number of bits set to 1 in the wrapped number.
   *
   * @return self
   */
  public function countBits()
  {
    
    $v = $this->value;
    $v = $v - (($v >> 1) & 0x55555555);
    $v = ($v & 0x33333333) + (($v >> 2) & 0x33333333);
    return new self((($v + ($v >> 4) & 0xF0F0F0F) * 0x1010101) >> 24);
    
  }

  
  ##
  ## INFO
  ##
  
  /**
   * Returns true if the number is finite.
   *
   * @return boolean
   */
  public function isFinite()
  {
    
    return is_finite($this->value);
    
  }
  
  /**
   * Returns true if the number is infinite.
   *
   * @return boolean
   */
  public function isInfinite()
  {
    
    return is_infinite($this->value);
    
  }
  
}
