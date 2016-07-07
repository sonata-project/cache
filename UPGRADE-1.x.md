UPGRADE 1.x
===========

## Changes in directory structure

The `lib` directory has been renamed to the more standard `src`.
This should not change anything for users since this library is meant to be used with Composer autoloading only.

### Tests

All files under the ``Tests`` directory are now correctly handled as internal test classes. 
You can't extend them anymore, because they are only loaded when running internal tests. 
More information can be found in the [composer docs](https://getcomposer.org/doc/04-schema.md#autoload-dev).
