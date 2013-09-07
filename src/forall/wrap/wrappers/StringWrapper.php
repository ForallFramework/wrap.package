<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\wrappers;

use \ArrayAccess;

class StringWrapper extends AbstractScalarWrapper implements ArrayAccess
{
  
  //Some constants.
  const TRIM_DEFAULTS = ' \t\n\r\0\x0B';
  const LEFT = -1;
  const RIGHT = 1;
  const BOTH = 0;
  
  /**
   * Validate and set the value.
   *
   * @param string $value
   * 
   * @throws WrapException If the given value was no string.
   */
  public function __construct($value)
  {
    
    //Validate.
    if(!is_string($value)){
      throw new WrapException(sprintf('Expecting $value to be string. %s given.', gettype($value)));
    }
    
    //Set.
    $this->value = $value;
    
  }
  
  /**
   * Get the character at the given index.
   * 
   * This method is used by PHP's ArrayAccess, and can thus be accessed through
   * `$self[$index]`.
   *
   * @param int $index
   *
   * @return self
   */
  public function offsetGet($index)
  {
    
    return new self($this->value{$index});
    
  }
  
  /**
   * Replace the character at the given index with the given value.
   * 
   * This method is used by PHP's ArrayAccess, and can thus be accessed through
   * `$self[$index] = $value`.
   *
   * @param int $index
   * @param string $value
   *
   * @return self Chaining enabled.
   */
  public function offsetSet($index, $value)
  {
    
    //Create and set the new value.
    $this->value = substr_replace($this->value, $value, $index, 1);
    
    //Enable chaining.
    return $this;
    
  }
  
  /**
   * Return true if the character at the given index exists.
   * 
   * This method is used by PHP's ArrayAccess, and can thus be accessed through stuff like
   * `isset($self[$index])` and `array_key_exists($index, $self)`.
   *
   * @param int $index
   *
   * @return boolean
   */
  public function offsetExists($index)
  {
    
    return ($this->length() >= ($index+1));
    
  }
  
  /**
   * Remove the character at the given index from the string.
   *
   * This method is used by PHP's ArrayAccess, and can thus be accessed through
   * `unset($self[$index])`.
   *
   * @param int $index
   *
   * @return self Chaining enabled.
   */
  public function offsetUnset($index)
  {
    
    //Remove the character at given index.
    $this->value = substr_replace($this->value, $index, '', 1);
    
    //Enable chaining.
    return $this;
    
  }
  
  /**
   * Return $this. ;)
   * 
   * Pretty much a no-op with enabled chaining.
   *
   * @return self $this.
   */
  public function toString()
  {
    
    return $this;
    
  }
  
  /**
   * Return a StringWrapper containing this string in JSON format.
   *
   * @return self
   */
  public function toJSON()
  {
    
    return $this->visualize();
    
  }
  
  /**
   * Return a StringWrapper containing the visual representation of this string.
   *
   * @return self
   */
  public function visualize()
  {
    
    return new self('"'.addslashes($this->value).'"');
    
  }
  
  /**
   * Return a NumberWrapper with the integer value of the string.
   *
   * @return NumberWrapper
   */
  public function toInt()
  {
    
    return new NumberWrapper(intval($this->value));
    
  }
  
  /**
   * Returns the wrapped alternative if the current value is empty.
   *
   * @param mixed $alternative Anything.
   *
   * @return AbstractWrapper $this Or the wrapped alternative.
   */
  public function alt($alternative)
  {
    
    return ($this->isEmpty() ? wrap($alternative) : $this);
    
  }
  
  /**
   * Trim specified characters off the start end end of the string.
   * 
   * @param int $side The side in which to trim. Possible values are
   *                  `StringWrapper::LEFT`, `StringWrapper::Right` and
   *                  `StringWrapper::BOTH`. This defaults to BOTH when omitted.
   * 
   * @param string $characters A string of characters to trim off whichever side is being
   *                           trimmed. Defaults to `self::TRIM_DEFAULTS` when omitted.
   * 
   * @return self
   */
  public function trim()
  {
    
    //Handle arguments.
    $args = func_get_args();
    
    //Get side.
    $side = ((!empty($args) && is_int($args[0])) ? array_shift($args) : self::BOTH);
    
    //Get characters.
    $characters = ((!empty($args)) ? array_shift($args) : self::TRIM_DEFAULTS);
    
    //Do the right trim.
    switch($side){
      case self::LEFT: return new self(ltrim($this->value, $characters));
      case self::BOTH: return new self(trim($this->value, $characters));
      case self::RIGHT: return new self(rtrim($this->value, $characters));
    }
    
  }
  
  /**
   * Prepend the given string to this one. Returns a new StringWrapper with the result.
   *
   * @see self::append()
   *
   * @param string $string The string to prepend.
   *
   * @return self
   */
  public function prepend($string)
  {
    
    return new self($string.$this->value);
    
  }
  
  /**
   * Append the given string to this one. Returns a new StringWrapper with the result.
   * 
   * @see self::prepend()
   *
   * @param string $string The string to append.
   *
   * @return self
   */
  public function append($string)
  {
    
    return new self($this->value.$string);
    
  }
  
  /**
   * Alias of trim(RIGHT).
   * 
   * @see self::trim()
   *
   * @param string $characters {@see self::trim()}
   *
   * @return self
   */
  public function chop($characters = self::TRIM_DEFAULTS)
  {
    
    return $this->trim(RIGHT, $characters);
    
  }
  
  /**
   * Pad the string to a certain length with another string.
   * 
   * @param int $side The side at which to pad. Possible values are
   *                  `StringWrapper::LEFT`, `StringWrapper::Right` and
   *                  `StringWrapper::BOTH`. This defaults to RIGHT when omitted.
   * 
   * @param int $length The amount of padding characters to add on whichever side is being
   *                    padded. If this parameter is negative, less than, or equal to the
   *                    length of the input string, no padding takes place.
   * 
   * @param string $padding The characters to add on whichever side is being padded. The
   *                        given string will repeat until the desired $length has been
   *                        reached. Defaults to spaces when omitted.
   * 
   * @return self
   */
  public function pad()
  {
    
    //Handle arguments.
    $args = func_get_args();
    
    //Get side.
    $side = ((count($args) > 1 && is_int($args[1])) ? array_shift($args) : RIGHT);
    
    //Get length.
    $length = array_shift($args);
    
    //Get padding.
    $padding = ((!empty($args)) ? array_shift($args) : ' ');
    
    //Define types.
    $types = [LEFT => STR_PAD_LEFT, BOTH => STR_PAD_BOTH, RIGHT => STR_PAD_RIGHT];
    
    //Go!
    return new self(str_pad($this->value, $length, $padding, $types[$side]));
    
  }
  
  /**
   * Cut off the string if it's longer than the given maximum, then append something to it.
   *
   * @param int $max The maximum allowed string length.
   * @param string $append An optional extra bit to append when cutting occurs.
   *
   * @return self
   */
  public function max($max, $append = '')
  {
    
    //Convert input to integer.
    $max = (int) $max;
    
    //Cut it up?
    if(strlen($this->value) > $max + strlen($append)){
      return new self(substr($this->value, 0, $max).$append);
    }
    
    //Do nothing.
    return new self($this->value);
    
  }
  
  /**
   * Repeat the string a given amount of times.
   *
   * @param int $n The amount of times to repeat.
   *
   * @return self
   */
  public function repeat($n)
  {
    
    return new self(str_repeat($this->value, $n));
    
  }
  
  /**
   * Replaces [search] with [replacement] and fills [count] with the amount of replacements done.
   *
   * @param string $search The sub-string to look for.
   * @param string $replacement The string to replace the occurrences with.
   * @param integer $count This is a reference that will be filled with the amount of occurrences.
   *
   * @return self
   */
  public function replace($search, $replacement='', &$count = 0)
  {
    
    return new self(str_replace($search, $replacement, $this->value, $count));
    
  }
  
  /**
   * Return a slice of the string.
   *
   * @param integer $offset Where to start the slice.
   * @param integer $length How long the slice should be. Slices to the end if omitted.
   *
   * @return self
   */
  public function slice($offset=0, $length=null)
  {
    
    return new self($offset >= $this->length()
      ? ''
      : (is_null($length)
        ? substr($this->value, $offset)
        : substr($this->value, $offset, $length)
      )
    );
    
  }
  
  /**
   * Perform a regular expression and return a wrapped array containing the matches.
   *
   * @param string $regex The regular expression to perform.
   * @param integer $flags Flags to pass to `preg_match`.
   * @param integer $offset At which offset (in bytes) to start matching.
   *
   * @return ArrayWrapper
   */
  public function parse($regex, $flags=0, $offset=0)
  {
    
    //Try to parse using the given arguments.
    preg_match($regex, $this->get(), $matches, $flags, $offset);
    
    //Return the matches.
    return new ArrayWrapper($matches);
    
  }
  
  /**
   * Return true if the wrapped string matches with the given regular expression.
   *
   * @param string $regex The regular expression to perform.
   * @param integer $flags Flags to pass to `preg_match`.
   * @param integer $offset At which offset (in bytes) to start matching.
   *
   * @return boolean
   */
  public function isMatch($regex, $flags=0, $offset=0)
  {
    
    return (preg_match($regex, $this->get(), $a, $flags, $offset) === 1);
    
  }
  
  /**
   * Return a new DataLeaf, containing the value of this one but in lower case.
   *
   * @return self
   */
  public function lowercase()
  {
    
    return new self(strtolower($this->value));
    
  }
  
  /**
   * Return a new DataLeaf, containing the value of this one but in upper case.
   *
   * @return self
   */
  public function uppercase()
  {
    
    return new self(strtoupper($this->value));
    
  }
  
  /**
   * Return a new DataLeaf containing the HTML escaped value of this node.
   *
   * @param integer $flags Flags to pass to `htmlentities`.
   *
   * @return self
   */
  public function htmlescape($flags=50)
  {
    
    return new self(htmlentities($this->value, $flags, 'UTF-8'));
    
  }
  
  /**
   * Parse the string as URL encrypted data and return the resulting array.
   *
   * @return ArrayWrapper
   */
  public function decode()
  {
    
    //Parse it.
    parse_str($this->value, $parsed);
    
    //Wrap and return.
    return new ArrayWrapper($parsed);
    
  }
  
  /**
   * Split the string up based on given splitter.
   *
   * @param int|string $s When an integer, splits the string up into chucks of that size.
   *                      When a string, splits the string on each occurrence of it.
   *                      When null or omitted, splits the string into characters.
   *
   * @return ArrayWrapper An array with each part that the string was split up into.
   */
  public function split($s=null)
  {
    
    //Return an empty array if the string is empty.
    if($this->isEmpty()){
      return new ArrayWrapper([]);
    }
    
    //Split the string into characters.
    if(empty($s) || (is_int($s) && $s < 1)){
      $split = str_split($this->value);
    }
    
    //Split the string into chunks.
    elseif(is_int($s)){
      $split = str_split($this->value, $s);
    }
    
    //Split the string on the given character.
    elseif(is_string($s)){
      $split = explode($s, $this->value);
    }
    
    return new ArrayWrapper($split);
    
  }
  
  /**
   * Return the length of the string contained in this node.
   *
   * @return int The amount of characters in the wrapped string.
   */
  public function length()
  {
    
    return strlen($this->value);
    
  }
  
  /**
   * Returns true of this string has no characters.
   *
   * @return boolean
   */
  public function isEmpty()
  {
    
    return empty($this->value);
    
  }
  
}
