<?php

/**
 * @package forall.wrap
 * @author Avaq <aldwin.vlasblom@gmail.com>
 */
namespace forall\wrap\arrayutils;

trait ArrayPermissionTraits
{
  
  use ArrayAccessTraits;
  
  /**
   * Holds a read / write / delete permission bitmap.
   *
   * @var integer
   */
  private $arr_permissions=7;
  
  //Permission setter.
  public function setArrayPermissions($read = true, $write = true, $delete = true)
  {
    
    $this->arr_permissions = ($read ? 1 : 0) | ($write ? 2 : 0) | ($delete ? 4 : 0);
    return $this;
    
  }
  
  //Permission check.
  public function arrayPermission($int)
  {
    
    return (($this->arr_permissions & $int) === $int);
    
  }
  
  #TODO: Override access methods to do permission checks.
  
}
