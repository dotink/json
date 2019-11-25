<?php

namespace Json;

/**
 * Provides serialization and normalization of all public / protected / private properties,
 * excluding those whose names begin with `_` (underscore).
 *
 * Standard conventions hold that properties beginning with underscores represent some sort of
 * meta information about the object and/or some state for something else working with it.  While
 * these are generally bad practice, they do exist.  If you're not using them, then this trait
 * is indistinguishable from `SerializeAllProperties`.
 */
trait SerializeStandardProperties
{
	public function jsonSerialize()
	{
		return array_map(
			[Normalizer::class, 'prepare'],
			array_filter(
				get_object_vars($this),
				function ($key) {
					return $key[0] != '_';
				},
				ARRAY_FILTER_USE_KEY
			)
		);
	}
}
