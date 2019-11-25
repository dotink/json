<?php

namespace Json\Normalizer;

/**
 * Normalizes a PHP `(object)` for JSON serialization
 */
class _Object extends \Json\Normalizer
{
	public function jsonSerialize()
	{
		return array_map([static::class, 'prepare'], get_object_vars($this('data')));
	}
}
