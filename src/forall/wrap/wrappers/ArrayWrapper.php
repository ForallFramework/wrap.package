<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\wrappers;

use \forall\wrap\arrayutils\ArrayContainerInterface;
use \forall\wrap\arrayutils\ArrayAccessTraits;
use \forall\wrap\arrayutils\ArrayGetterTraits;
use \forall\wrap\arrayutils\ArrayInformationTraits;
use \forall\wrap\arrayutils\ArrayIteratorTraits;
use \forall\wrap\arrayutils\ArrayMagicTraits;
use \forall\wrap\arrayutils\ArraySetterTraits;
use \IteratorAggregate;
use \ArrayAccess;
use \ArrayIterator;
use \Closure;

class ArrayWrapper extends AbstractWrapper implements IteratorAggregate, ArrayAccess, ArrayContainerInterface
{
  
  //Use a whole bunch of the array utilities.
  use ArrayAccessTraits;
  use ArrayGetterTraits;
  use ArrayInformationTraits;
  use ArrayIteratorTraits;
  use ArrayMagicTraits;
  use ArraySetterTraits;
  
  //Define some constants.
  const LEFT = -1;
  const RIGHT = 1;
  
  /**
   * Contains the wrapped array.
   *
   * @var array
   */
  private $arr;
  
  /**
   * The constructor sets the array.
   *
   * @param array $arr
   */
  public function __construct(array $arr = [])
  {
    
    $this->arr = $arr;
    
  }
  
  /**
   * Returns a reference to the wrapped array.
   *
   * @return array
   */
  public function & getArrayReference()
  {
    
    return $this->arr;
    
  }
  
  ##
  ## MUTATION METHODS
  ##
  
  /**
   * Cast this to string and return the result in a StringWrapper.
   *
   * @return StringWrapper
   */
  public function toString()
  {
    
    return new StringWrapper('['.__CLASS__.']');
    
  }
  
  /**
   * JSON encode the array and return the result in a StringWrapper.
   *
   * Chooses intelligently whether to use a JSON object or an array.
   *
   * @return StringWrapper Containing a string of JSON data.
   */
  public function toJSON()
  {
    
    //Store whether this is an associative array.
    $assoc = $this->isAssociative();
    
    //Start the chain.
    return $this
    
    //First step of the intelligent conversion to JSON.
    ->map(function($node, $key)use($assoc){
      return ($assoc ? wrap($key)->toJSON().':' : '').$this->wrapRaw($key)->toJSON();
    })
    
    //Second step of the intelligent conversion to JSON.
    ->join(', ')
    ->prepend($assoc ? '{' : '[')
    ->append($assoc ? '}' : ']');
    
  }
  
  /**
   * Return a StringWrapper containing the visual representation of this array.
   *
   * Every node within the array is also visualized, unless $short is true.
   *
   * @param boolean $short When set to true, only show the length of the array.
   *
   * @return StringWrapper
   */
  public function visualize($short=false)
  {
    
    //Make a short visualization?
    if($short){
      return new StringWrapper('array('.$this->size().')');
    }
    
    //Start the chain.
    return $this
    
    //Conversion step 1.
    ->map(function($node, $key){
      return wrap($key)->visualize().' => '.wrap($node)->visualize();
    })
    
    //Conversion step 2.
    ->join(', ')
    ->prepend('[')
    ->append(']');
    
  }
  
  /**
   * Return a new ArrayWrapper with the keys of this array as values.
   *
   * @return self
   */
  public function keys()
  {
    
    return new self(array_keys($this->arr));
    
  }
  
  /**
   * Return a new ArrayObject with the values of this array as values.
   *
   * @return self
   */
  public function values()
  {
    
    return new self(array_values($this->arr));
    
  }
  
  /**
   * Create a map using the wrapped array.
   *
   * Returns a new ArrayObject by iterating over the data and using the return value from
   * the callback to populate a new array.
   *
   * @param Closure $callback The closure to use for the mapping. It is called with three
   *                          arguments: The value, the key and the index.
   *
   * @return self The result of the mapping
   */
  public function map(Closure $callback)
  {
    
    //Create the new instance and set a counter at zero.
    $return = new self;
    $i = 0;
    
    //Call the given callback for every node, pushing its return value into the new array.
    foreach($this->arr as $key => $value){
      $return->push($callback($value, $key, $i));
      $i++;
    }
    
    //Return the new instance, filled with the return values of the called callbacks.
    return $return;
  
  }
  
  /**
   * Return a new ArrayWrapper filled with the nodes that were at the given keys.
   *
   * This method is basically shorthand for calling self::map(), where the given closure
   * returns a sub-node from every iteration.
   *
   * @see self::map()
   *
   * @param mixed $key The first key to look for.
   * @param mixed ... The above parameter repeats indefinitely.
   *
   * @return self
   */
  public function pluck()
  {
    
    
    //Create a new instance.
    $return = new self;
    
    //For every node in our current array.
    foreach($this->arr as $node)
    {
      
      //Grab the sub node of the current node for every argument passed to this function.
      foreach(func_get_args() as $key){
        $node = $node[$key];
      }
      
      //Push the last node into the new instance.
      $return->push($node);
      
    }
    
    //Return the new instance.
    return $return;
    
  }
  
  /**
   * Return a new ArrayObject, excluding the nodes that were not in the given keys.
   *
   * One can optionally specify a new name for a key by setting it as the key. This only
   * works for associative (string) keys.
   *
   * @param array $keys The keys that will be included, and optionally their new names.
   *
   * @return self
   */
  public function having(array $keys)
  {
    
    //Create a new instance.
    $return = new self;
    
    //For every given key.
    foreach($keys as $key1 => $key2)
    {
      
      //If a new key name has been given.
      if(is_string($key1)){
        $return->arraySet($key1, $this->offsetGet($key2));
      }
      
      //Use the same key name.
      else{
        $return->arraySet($key2, $this->offsetGet($key2));
      }
      
    }
    
    //Return the new instance.
    return $return;
    
  }
  
  /**
   * Returns a new ArrayObject, excluding the nodes that were in the given keys.
   *
   * Does the same as `self::having()`, except it excludes instead of includes.
   *
   * @see self::having()
   *
   * @param array $keys The keys that will be excluded.
   *
   * @return self
   */
  public function without(array $keys)
  {
    
    //Return a new instance with the computed difference in keys between the current array and given keys.
    return new self(array_diff($this->arr, array_fill_keys($keys, null)));
    
  }
  
  /**
   * Return a new ArrayObject containing only the nodes that made the given callback return true.
   *
   * The wrapped array is iterated, and for each node the given callback will be executed.
   * The node will make it into the return array if the callback returned true for it.
   *
   * @param Closure $callback The closure to use for the truth-test. It is passed two
   *                          parameters: The value of the node, and the key.
   *
   * @return self
   */
  public function filter(Closure $callback)
  {
    
    //Create the new instance.
    $return = new self;
    
    //For every node, call the callback and add the node to the instance of the callback returned true.
    foreach($this->arr as $k => $v){
      if($callback($v, $k) === true){
        $return->arraySet($k, $v);
      }
    }
    
    //Return the new instance.
    return $return;
    
  }
  
  /**
   * Returns a slice of the wrapped array in a new ArrayObject.
   *
   * @param integer $offset Where to start slicing.
   * @param integer $length After how many characters to stop slicing. When omitted, the
   *                        slicing won't stop until after the last node.
   *
   * @return self
   */
  public function slice($offset=0, $length=null)
  {
    
    //Return a new instance with a slice of the current array.
    return new self(array_slice($this->arr, $offset, $length));
    
  }
  
  /**
   * Flatten the array and return a flat new ArrayObject.
   *
   * @return self
   */
  public function flatten()
  {
    
    //Initialize variables.
    $output = [];
    $input = $this->toArray();
    
    //Flattening action!
    array_walk_recursive($input, function($a)use(&$output){
      $output[] = $a;
    });
    
    //Wrap and return.
    return new self($output);
    
  }
  
  /**
   * Boils down the array of values into a single value.
   *
   * @param int $mode Whether to reduce left or reduce right. Can be omitted. Possible
   *                  values are: `ArrayWrapper::LEFT`, `ArrayWrapper::RIGHT` or `LEFT`
   *                  and `RIGHT` from the *globalconstants* package. Defaults to
   *                  `self::LEFT`.
   *
   * @param callable $callback The callback to use during reduction. This is passed three
   *                           parameters: The output from the previous iteration, the
   *                           value of the current node and the key of the current node.
   *
   * @param mixed $initial The initial value to pass during the first iteration.
   *                       Defaults to NULL when omitted.
   *
   * @return AbstractWrapper A new wrapper containing the output of the last iteration.
   */
  public function reduce()
  {
    
    //Handle arguments.
    $args = func_get_args();
    
    //Get mode.
    $mode = ((is_int($args[0])) ? array_shift($args) : self::LEFT);
    
    //Get callback.
    $callback = array_shift($args);
    
    //Get initial output value.
    $output = ((count($args) > 0) ? array_shift($args) : null);
    
    //Get array.
    $array = ($mode < 0 ? $this->arr : array_reverse($this->arr));
    
    //Iterate.
    foreach($array as $key => $value){
      $output = $callback($output, $value, $key);
    }
    
    //Return the wrapped output.
    return wrap($output);
    
  }
  
  /**
   * Returns a string created of all values converted to strings and joined together.
   *
   * @param string $separator Defaults to an empty string when omitted.
   *
   * @return StringWrapper
   */
  public function join($separator='')
  {
    
    return new StringWrapper(implode($separator, $this->arr));
    
  }
  
  /**
   * Returns a string created of all keys and values converted to strings and joined together.
   *
   * Like join, but instead of using just the values it will concatenate the key and value
   * together using the delimiter.
   *
   * @param string $delimiter This is what goes in between each key and value.
   *                          Defaults to an empty string when omitted.
   *
   * @param string $separator This is what goes in between each pair.
   *                          Defaults to an empty string when omitted.
   *
   * @return StringWrapper
   */
  public function joinWithKeys($delimiter='', $separator='')
  {
    
    //Initialize variables.
    $implode = '';
    $array = $this->arr;
    
    //Iterate the array, grabbing key, value and iteration index. Append string.
    for($i=1, $size = count($array); list($key, $value) = each($array), $i <= $size; $i++){
      $implode .= "$key$separator$value".($i<$size?$delimitter:'');
    }
    
    //Return a new StringWrapper with the result of the implosion.
    return new StringWrapper($implode);
    
  }
  
  /**
   * Return the key at the first node with the given value.
   *
   * @param mixed $value The value to look for.
   * @param boolean $strict Whether to find it using strict matching.
   *
   * @return AbstractWrapper The wrapped result of the search.
   */
  public function search($value, $strict = false)
  {
    
    return wrap($this->keyAt($value, $strict));
    
  }
  
  /**
   * Recursively search through the array and return an array of keys, leading to the first match found.
   *
   * @param mixed $needle The value to look for.
   * @param integer $offset The depth at which to start looking.
   * @param boolean $strict Whether to find the value using strict matching.
   *
   * @return ArrayWrapper An array of keys found.
   */
  public function searchRecursive($needle, $offset=0, $strict=false)
  {
    
    //Create the iterator closure.
    $iterator = function($haystack, $depth = 0)use(&$needle, &$offset, &$strict, &$iterator){
      
      //Iterate over the haystack.
      foreach($haystack AS $key => $value)
      {
        
        //If the value is an array.
        if(is_array($value))
        {
          
          //Iterate over the sub-nodes.
          $keys = $iterator($value, ($depth+1));
          
          //If no match was found, continue to the next iteration.
          if(empty($keys)){
            continue;
          }
          
          //A match was found. Add our own key and cancel the iteration. We're done.
          array_unshift($keys, $key);
          break;
          
        }
        
        //Match the values if we're passed our offset depth.
        if(($depth >= $offset) && ($strict === true ? ($value === $needle) : ($value == $needle))){
          $keys = [$key];
          break;
        }
        
      }
      
      //Return the keys.
      return isset($keys) ? $keys : [];
      
    };
    
    //Return the wrapped result of the iterator.
    return wrap($iterator($this->arr));
    
  }
  
  
  ##
  ## GETTERS
  ##
  
  /**
   * Get the native value.
   *
   * @return array The wrapped array.
   */
  public function get()
  {
    
    return $this->arr;
    
  }
  
  /**
   * Return the node under the given key wrapped in a new Data object.
   *
   * This is short for doing: `forall\wrap\wrap($this->offsetGet($key))`.
   *
   * @param mixed $key The key of the sub-node to wrap.
   *
   * @return AbstractWrapper A wrapper containing whatever was inside that node.
   */
  public function wrap($key)
  {
    
    //Return a NullWrapper if the node does not exist.
    if(!$this->offsetExists($key)){
      return new NullWrapper;
    }
    
    //Wrap and return.
    return wrap($this->offsetGet($key));
    
  }
  
  /**
   * Return the node under [key] if it was wrapped, otherwise wrap it first.
   *
   * Does the same as `self::wrap`, except skips the wrapping if the node already contains
   * a wrapper.
   *
   * @see self::wrap()
   *
   * @param mixed $key The key of the sun-node to wrap or return.
   *
   * @return [type] [description]
   */
  public function wrapRaw($key)
  {
    
    //Return a NullWrapper if the node does not exist.
    if(!$this->offsetExists($key)){
      return new NullWrapper;
    }
    
    //Return the node.
    return wrapRaw($this->offsetGet($key));
    
  }
  
  /**
   * Return the wrapped alternative if this array is empty.
   *
   * @param mixed $alternative Anything you want.
   *
   * @return AbstractWrapper Either $this, or the given alternative inside a new wrapper.
   */
  public function alt($alternative)
  {
    
    return ($this->isEmpty() ? wrap($alternative) : $this);
    
  }
  
  
  ##
  ## INFORMATION METHODS
  ##
  
  /**
   * Return false if all keys are numeric.
   *
   * @return boolean Whether the wrapped array contains any non-numeric keys.
   */
  public function isAssociative()
  {
    
    return ! $this->every(function($val, $key){
      return is_numeric($key);
    });
    
  }
  
  
  ##
  ## ARRAY METHODS
  ##
    
  /**
   * Returns an ArrayIterator wrapping the wrapped array.
   *
   * This is mainly for PHP's internal use.
   *
   * @return ArrayIterator
   */
  public function getIterator()
  {
    
    return new ArrayIterator($this->arr);
    
  }
  
}
