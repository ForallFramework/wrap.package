<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\successible;

interface SuccessibleInterface
{
  
  /**
   * Should set the success state of the object to whatever $check evaluates to.
   * 
   * When $check is a Closure, the success state should be the return value of a call to
   * it with $this passed as first argument. When $check is another instance implementing
   * this interface, the success state of that should be used. When $check is anything
   * else, the value of it cast to boolean should be used.
   *
   * @param  mixed  $check  Anything that will evaluate to a boolean with the above rule set.
   *
   * @return self           Chaining enabled.
   */
  public function is($check);
  
  /**
   * Should do the exact same as self::is, except with an inverse boolean.
   *
   * @see self::is() for more information.
   *
   * @param  mixed $check
   *
   * @return self Chaining enabled.
   */
  public function not($check);
  
  /**
   * Should do the exact same as self::is, except only when the current success state is already true.
   *
   * @see self::is() for more information.
   *
   * @param  mixed $check
   *
   * @return self Chaining enabled.
   */
  public function andIs($check);
  
  /**
   * Should do the exact same as self::not, except only when the current success state is already true.
   *
   * @param  mixed $check
   *
   * @return self Chaining enabled.
   */
  public function andNot($check);
  
  /**
   * Should call the given callback if the current success state is true, or return the success state.
   * 
   * If no callback is given, this function should return the current success state. If a
   * callback is given, it should be called, and the return value of it should be returned
   * if that is an instance implementing this interface. Otherwise this instance should be
   * returned.
   *
   * @param  callable $callback An optional callback to execute instead of returning the success state.
   *
   * @return bool|SuccessibleInterface Based on if the callback was provided, and what it returned.
   */
  public function success(callable $callback = null);
  
  /**
   * Should do the exact same as self::success, except "reversed".
   * 
   * By reversed I mean, call the given callback only if the success state is false, and
   * return the inverse success state if the callback wasn't given.
   * 
   * @see self::success() for more information.
   *
   * @param  callable $callback
   *
   * @return bool|SuccessibleInterface
   */
  public function failure(callable $callback = null);
  
}
