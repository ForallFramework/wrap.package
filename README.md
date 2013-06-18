# Wrap - Version 0.0.1 Alpha

## Description

The "wrap" package for the Forall Framework. This is a utility library that provides
wrappers for all native data types, allowing them to be wrapped for access to a number
of utility functions. 

## Features

* Wrappers for every native data type.
* An interface for making any object "successible".

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

## Change log

The change-log can be found in `CHANGES.md` in this directory.

## License

Copyright (c) 2013 Avaq, https://github.com/Avaq

Forall is licensed under the MIT license. The license is included as `LICENSE.md` in the 
[Forall environment repository](https://github.com/ForallFramework/Forall).
