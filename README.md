# VirtualInfo
Provide `private` and `protected` indicators to customized `var_dump` output

## Rationale
As of PHP 5.6 a new magic method has been made available; [`__debugInfo`](http://php.net/manual/en/language.oop5.magic.php#object.debuginfo). This method makes it possible to manipulate the output provided by [`var_dump`](http://php.net/manual/en/function.var-dump.php), but it will always claim all properties being `public`, this is where `VirtualInfo` comes in, it provides a static library to have the option to recreate the `private` and `protected` keywords.

## Examples
### Default behavior
By default the `__debugInfo` will make all exposed properties `public` (or no indication at all):

```php
class Example {
	//  __debugInfo faking public properties
	public function __debugInfo() {
		return [
			'foo' => 'bar',
			'bar' => 'baz',
			'baz' => 'qux'
		];
	}
}
```

Leading to output similar to (CLI):
```
object(Example)#1 (3) {
  ["foo"]=>
  string(3) "bar"
  ["bar"]=>
  string(3) "baz"
  ["baz"]=>
  string(3) "qux"
}
```

or with the magnificent [XDebug](http://xdebug.org/) installed:
```
class Example#1 (3) {
  public $foo =>
  string(3) "bar"
  public $bar =>
  string(3) "baz"
  public $baz =>
  string(3) "qux"
}
```

### Using VirtualInfo
In order to have `private` and/or `protected` properties again, simply include `virtualinfo.php` and modify the `__debugInfo` method to use it:

```php
require('virtualinfo.php');  //  or autoload it..

class Example {
	//  __debugInfo faking public properties
	public function __debugInfo() {
		return [
			//  foo remains public
			'foo' => 'bar',
			//  bar will be protected
			Konfirm\VirtualInfo::prot('bar') => 'baz',
			//  baz will be private
			Konfirm\VirtualInfo::priv('baz') => 'qux'
		];
	}
}
```

Now the output includes `private`, `protected` and `public` members:
```
object(Example)#1 (3) {
  ["foo"]=>
  string(3) "bar"
  ["bar":protected]=>
  string(3) "baz"
  ["baz":"VirtualInfo":private]=>
  string(3) "qux"
}
```

or if you use [XDebug](http://xdebug.org), it would look like:
```
class Example#1 (3) {
  public $foo =>
  string(3) "bar"
  protected $bar =>
  string(3) "baz"
  private $baz =>
  string(3) "qux"
}
```

## API
The API is simple and straight forward

### `string  VirtualInfo::pub(string)`
Take the input string and return its 'public' counterpart (hint: this method returns the input, as `public` is the default)

### `string  VirtualInfo::prot(string)`
Take the input string and return its `protected` counterpart

### `string  VirtualInfo::priv(string)`
Take the input string and return its `private` counterpart

### `Array  VirtualInfo::info(Array key/value)`
Take an array and map the special notation in its keys to the specified format.
The format is determined by a set of predetermined prefixes:
- `@`, `priv:`, `private:` before the variable name indicate `private`
- `*`, `prot:`, `protect:`, `protected:` before the variable name indicate `protected`
- everything else will be public, where `pub:` and `public:` will be stripped off the key (in order to have a consistent declaration)

Example
```php
VirtualInfo::info([
	'pub:foo1'       => 'public foo1',
	'public:foo2'    => 'public foo2',
	'pblc:foo3'      => 'public pblc:foo3',

	'*bar1'          => 'protected bar1',
	'prot:bar2'      => 'protected bar2',
	'protect:bar3'   => 'protected bar3',
	'protected:bar4' => 'protected bar4',
	'prtctd:bar5'    => 'public prtctd:bar4',

	'@baz1'          => 'private baz1',
	'priv:baz2'      => 'private baz2',
	'private:baz3'   => 'private baz3',
	'prvt:baz4'      => 'public prvt:baz4',
]);
```

## License
GPLv2 © [Konfirm ![Open](https://kon.fm/open.svg)](//kon.fm/site)
