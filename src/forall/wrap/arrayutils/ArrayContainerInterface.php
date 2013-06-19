<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\arrayutils;

interface ArrayContainerInterface
{
  
  /**
   * Return a reference to the contained array.
   * 
   * @return array The contained array.
   */
  public function & getArrayReference();
  
}
