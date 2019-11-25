<?php

namespace Json;

/**
 * The `Serialize` trait can be added to any class that implements `Json\Serializable` in order
 * to ensure it will be normalized by a standard `json_encode` call.
 *
 * This trait is not necessary if the `Json\Serialize()` function is used in place of the native
 * `json_encode` function.
 */
trait Serialize
{
	public function jsonSerialize()
	{
		//
		// We want to prepare the object, but we want to skip over the JsonSerialable normalizers
		// because we already know we are the json serializable method.  Otherwise the prepared
		// object may end up in an ifinite prepare loop.
		//

		return Normalizer::prepare($this, FALSE, [
			\JsonSerializable::class,
			\Json\Serializable::class
		]);
	}
}
