<?php

namespace Json\Normalizer;

/**
 * Normalizes a PHP `DateTime` object for JSON serialization
 */
class DateTime extends \Json\Normalizer
{
	public function jsonSerialize()
	{
		return static::prepare($this->format('c'), $this('nested'));
	}
}
