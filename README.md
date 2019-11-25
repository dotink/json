Think of this library as a way to move your `jsonSerialize()` method to a separate class that can be dependency injected, access protected/private properties and methods on their parent classes, and more.

## Installation

```
composer require dotink/json
```

## Usage

```php
$json = Json\Serialize($data);
```

The above function will prepare your `$data` using available normalizers registered in the `Json\Normalizer` namespace (by default).  For example, if your `$data` is an array it will use the `Json\Normalizer\_Array` normalizer which will, in turn, normalize all the elements of the array.

### Object Normalization

Normalizing objects operates quite a bit differently.

Which normalizer is used depends on whether or not there is a corresponding normalizer available in the `Json\Normalizer` namespace (by default).  If no corresponding normalizer can be found, it will revert to `Json\Normalizer\_Object` which will normalize all public properties.

For example, included in this library is a `Json\Normalizer\DateTime` class:

```php
namespace Json\Normalizer;

class DateTime extends \Json\Normalizer
{
	public function jsonSerialize()
	{
		return $this->format('c');
	}
}
```

So if your object is of the class `DateTime`, it will use that normalizer.

Compare, for example:

```php
Json\Serialize(new DateTime('today'))
```

Which outputs:

```php
"2019-11-25T00:00:00-08:00"
```

To:

```php
json_encode(new DateTime('today'))
```

Which outputs:

```php
"{"date":"2019-11-25 00:00:00.000000","timezone_type":3,"timezone":"America\/Los_Angeles"}"
```

### Adding Normalizers

To add a custom object normalizer, simply create a new normalizer whose full class name (includes namespace) is prefixed by `Json\Normalizer`.  If you want to normalize `My\Library\Acme` you would create `Json\Normalizer\My\Library\Acme`:

```php
namespace Json\Normalizer\My\Library;

class Acme extends \Json\Normalizer
{
	public function jsonSerialize()
	{
		//
		// return normalized object
		//
	}
}
```

### What If I Don't Want to Use This Libraries Existing Normalizers?

You can change the namespace in which normalizers are looked for:

```php
Json\Normalizer::setNamespace('My\Json\Normalizer')
```

### What If I Need Additional Dependencies to Normalize My Objects?

You can register any PSR-11 compatible container to resolve/construct your normalizers:

```php
Json\Normalizer::setContainer($container);
```

### What If I Need To Normalize My Object Differently If It's Nested In Another Object?

You can determine whether or not the normalizer is nested using `$this('nested')`:

```php
namespace Json\Normalizer\My\Library;

class Acme extends \Json\Normalizer
{
	public function jsonSerialize()
	{
		if ($this('nested')) {

			//
			// Return nested object's normalization
			//

		} else {

			//
			// Return non-nested object's normalization
			//

		}
	}
}
```

### What If I Need To Access Protected/Private Properties/Methods When Normalizing My Objects?

Normalizers proxy all instance property/method calls to the underlying object and will use reflection to access the data/method if it's not accessible, so calling `$this->protectedProperty` or `$this->privateMethod()` will work as if the normalizer's `jsonSerialize()` method was on the class itself.

### Will Child Classes Be Using the Parent's Normalizer?

Yes.  Using the previous example `My\Library\AcmeChild` which extends `My\Library\Acme` would use `Json\Normalizer\My\Library\Acme` unless there was a `Json\Normalizer\My\Library\AcmeChild`.

### Will My Existing `JsonSerializable` Objects Be Normalized?

Yes.  If your object implements `JsonSerializable` there is a `Json\Normalizer\JsonSerializable` normalizer which will normalize the return value of that method.

### What If I Need to Normalize Objects With the Standard `json_encode()`?

You can add custom normalization for objects regardless of whether or not they are encoded via `Json\Serialize()` or `json_encode()` by using the `Json\Serialize` trait:

```php
class Acme implements Json\Serializable
{
	use Json\Serialize;
}
```

> NOTE: `Json\Serializable` is just a concrete stand-in interface for PHP's built-in `JsonSerializable`.  If your object already implements `JsonSerializable` move the existing `jsonSerialize()` method to a custom normalizer.

### What If I Just Want to Normalize All Accessible Properties?

You can use `Json\SerializeAllProperties` on your class instead of `Json\Serialize`:

```php
class Acme implements Json\Serializable
{
	use Json\SerializeAllProperties;
}
```

### What If I Have Super-Secret Properties That Shouldn't Be Normalized... Starting with `_`?

You can use `Json\SerializeStandardProperties` on your class instead of `Json\SerializeAllProperties`:

```php
class Acme implements Json\Serializable
{
	use Json\SerializeStandardProperties;
}
```

### What If I Want To Normalize All Strings As "I'm a teapot?"

You can add the following class and make it autoloadable:

```php
namespace Json\Normalizer;

class _String extends \Json\Normalizer
{
	public function jsonSerialize()
	{
		return "I'm a teapot";
	}
}
```

### What If I Want To Serialize To XML?

GTFO.

## Caveats

Normalization works by implementing `JsonSerializable` and wrapping data in normalizers that implement it.  Since it is not possible to modify an built-in PHP class such as `DateTime` to directly implement `JsonSerializable` then encoding built-in PHP objects can produce different results using `Json\Serialize` and `json_encode()`.

It is, of course, possible to extend these objects and use `Json\Serialize`, but you will need to replace instantiation of such with the child class:

```php
namespace My;

class DateTime extends \DateTime implements Json\Serializable
{
	use Json\Serialize;
}
```

## Why Does This Exist?

The traditional PHP `json_encode()` function is capable of providing classes/objects a mechanism with which they can normalize their data to be serialized.  The mechanism that does this is the `JsonSerializable` interface and the corresponding `jsonSerialize()` method.  While this approach solves many basic cases of normalization, it does not solve many more advanced cases.

This library was written to solve a specific case wherein:

1. I needed a way to have standard `json_encode` serialize objects.
2. What data was serialized for those objects depended on if they were nested or not.
3. The normalization of data needed to be performed with additional dependencies which could not be injected into the objects, and probably shouldn't be just for the sake of their self-normalization.

### Why Not Use a Totally Separate Serialization Library?

While there are many great serialization libraries available, getting a serialization library into every place where `json_encode` might be used currently is not a realistic option.  Additionally, without any sort of standard serialization interface, other libraries will likely still continue to use `json_encode()`.  See point #1.
