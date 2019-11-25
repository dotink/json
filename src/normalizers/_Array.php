<?php

namespace Json\Normalizer;

/**
 * Normalizes a PHP `(array)` for JSON serialization
 */
class _Array extends \Json\Normalizer
{
	public function jsonSerialize()
	{
		return array_map([static::class, 'prepare'], $this('data'));
	}
}
