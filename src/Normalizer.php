<?php

namespace Json;

use Closure;
use RuntimeException;
use ReflectionObject;
use Psr\Container\ContainerInterface;

/**
 * An abstract normalizer
 *
 * Normalizers are responsible for wrapping data and implementing `Json\Serializable`, an alias
 * for the built in PHP `JsonSerializable` interface.
 */
abstract class Normalizer implements Serializable
{
	/**
	 * A PSR-11 container for creating new normalizers
	 *
	 * @var ContainerInterface|null
	 */
	private static $container = NULL;


	/**
	 * The namespace in which to look for normalizers
	 *
	 * @var string
	 */
	private static $namespace = NULL;


	/**
	 * The data on which this normalizer operates
	 *
	 * @var mixed
	 */
	protected $data = NULL;


	/**
	 * Whether or not this normalizer is representing a nested value
	 *
	 * @var bool
	 */
	protected $nested = TRUE;


	/**
	 * The reflection object for property/method access
	 *
	 * @var ReflectionObject
	 */
	protected $reflection = NULL;


	/**
	 * Prepare a piece of data by locating and wrapping it in a normalizer if one is found.
	 *
	 * @var mixed $data The piece of data to be prepared
	 * @return mixed A matching normalizer containing the data, original data if none exists
	 */
	final public static function prepare($data, bool $nested = TRUE, array $skip = array())
	{
		$normalizers = ['_' . ucwords(gettype($data))];

		if (is_object($data)) {
			if ($data instanceof Normalizer) {
				return $data;
			}

			$normalizers = array_merge(
				[get_class($data)],
				class_parents($data),
				class_implements($data),
				$normalizers
			);
		}

		foreach (array_diff($normalizers, $skip) as $normalizer) {
			$normalizer = (self::$namespace ?: 'Json\\Normalizer') . '\\' . $normalizer;

			if (class_exists($normalizer)) {
				return self::create($normalizer, $data, $nested);
			}
		}

		return $data;
	}


	/**
	 * Set the container for creating new normalizers
	 */
	final public static function setContainer(?ContainerInterface $container)
	{
		self::$container = $container;
	}


	/**
	 * Set the namespace in which to look for normalizers
	 */
	final public static function setNamespace(?string $namespace)
	{
		self::$namespace = $namespace;
	}


	/**
	 * Create a new normalizer
	 *
	 * @return Normalizer The normalizer with the requisite data sent
	 * @throws RuntimeException If the requested normalizer class is not a subclass of Normalizer
	 */
	private static function create(string $normalizer, $data, bool $nested)
	{
		if (!is_subclass_of($normalizer, __CLASS__)) {
			throw new RuntimeException(sprintf(
				'Serializer class "%s" is invalid, must extend "%s"',
				$normalizer,
				__CLASS__
			));
		}

		if (self::$container) {
			$instance = self::$container->get($normalizer);

		} else {
			$instance = new $normalizer();
		}

		$instance->data   = $data;
		$instance->nested = $nested;

		return $instance;
	}


	/**
	 * Proxy instance methods to the underlying data and provide access if inaccessible
	 *
	 * @var string $method The name of the method to call on underlying data
	 * @var array $args The arguments to pass to the method
	 * @return mixed The result of calling the method with args on the underlying data
	 */
	public function __call($method, $args)
	{
		if (!method_exists($this->data, $method)) {
			throw new RuntimeException(
				'Cannot normalize "%s", attempt to access non-existent method "%s"',
				get_class($this->data),
				$method
			);
		}

		if (!$this->reflection) {
			$this->reflection = new ReflectionObject($this->data);
		}

		$method = $this->reflection->getMethod($method);

		if (!$method->isPublic()) {
			$method->setAccessible(TRUE);
		}

		return $method->invoke($this->data, ...$args);
	}


	/**
	 * Proxy instance properties to the underlying data and provide access if inaccessible
	 *
	 * @var string $property The name of the property to get on the underlying data
	 * @return mixed The value of the property on the underlying data
	 */
	public function __get($property)
	{
		if (!property_exists($this->data, $property)) {
			throw new RuntimeException(
				'Cannot normalize "%s", attempt to access non-existent property "%s"',
				get_class($this->data),
				$property
			);
		}

		if (!$this->reflection) {
			$this->reflection = new ReflectionObject($this->data);
		}

		$property = $this->reflection->getProperty($property);

		if (!$property->isPublic()) {
			$property->setAccessible(TRUE);
		}

		return $property->getValue($this->data);
	}


	/**
	 * Access normalizer information
	 *
	 * Since properties/methods are proxied to the underlying data, you can ue `$this('data')` or
	 * `$this('nested')` in the context of a normalizer to access the underlying data or the value
	 * of `nested` property on the normalizer itself.  If you need to invoke the underlying data
	 * you can do so with $this('data')().
	 *
	 * @var string $property The name of the property to access on the normalizer
	 * @return mixed The value of hte property on the normalizer
	 */
	public function __invoke($property)
	{
		return $this->{(string) $property};
	}
}
