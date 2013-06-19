# Wrap - Version 0.0.2 Alpha

## Description

The "wrap" package for the Forall Framework. This is a utility library that provides
wrappers for all native data types, allowing them to be wrapped for access to a number
of utility functions. 

## Features

* Wrappers for every native data type.
* An interface for making any object "successible".
* A library of traits for working with a class containing an array.

### Wrappers

None implemented yet.

### Successible

This provides a system to do "if checks" inside a method chain.

Make a class successible by implementing `forall\wrap\successible\SuccessibleInterface`
into your class. The interface requires 6 methods to be implemented, but all are covered
by the `forall\wrap\successible\SuccessibleTraits` trait.

Now you can write code like this:

```php
//Make a new instance of a class that has the interface properly implemented.
$foo = new SuccessibleObject;

//Start chaining methods.
$foo

# if(1==1)
->is(1==1)

# if($foo->test == 'hi')
->andIs(function($foo){
  return $foo->test == 'hi';
})

//Both conditions are met (note the andIs method).
->success(function(){
  echo 'Yay!'
});
```

The successible classes are used for all wrapping classes to provide many of the higher-
level checks such as `$stringWrapper::eq('hi')`.

### Array utilities

Implement `forall\wrap\arrayutils\ArrayContainerInterface` into your class, and now you
can use a wide range of traits (all available in the `forall\wrap\arrayutils` name space)
that add methods for interacting with a single array within the class.

#### `ArrayAccessTraits`

Use this trait to add low-level interaction methods for getting values, setting values,
unsetting values, and checking keys. This trait also implements all methods required by
[PHP's `ArrayAccess`](http://uk1.php.net/manual/en/class.arrayaccess.php) interface.

#### `ArrayGetterTraits`

Use this trait to add high-level getter methods that allow for a number of different ways
to access nodes inside the array.

#### `ArrayInformationTraits`

Use this trait to add methods that provide information about the array, like the
number of nodes or whether a value exists.

#### `ArrayIterationTraits`

Use this trait to add low-level iteration methods for custom step-by-step iteration.

#### `ArrayIteratorTraits`

Use this trait to add high-level iteration methods that allow fr quick and easy iteration
of the entire array.

#### `ArrayPermissionTraits`

Use this trait to add methods through which the owner of the class can get some control
over what is allowed to happen with the array.

#### `ArraySetterTraits`

Use this trait to add high-level setter methods that allow for a number of different ways
to change nodes inside the array.


## Change log

The change-log can be found in `CHANGES.md` in this directory.

## License

Copyright (c) 2013 Avaq, https://github.com/Avaq

Forall is licensed under the MIT license. The license is included as `LICENSE.md` in the 
[Forall environment repository](https://github.com/ForallFramework/Forall).
