<?php

namespace Json;

/**
 * Provides serialization and normalization of all public / protected / private properties,
 * regardless of name.
 */
trait SerializeAllProperties
{
	public function jsonSerialize()
	{
		return array_map([Normalizer::class, 'prepare'], get_object_vars($this));
	}
}
