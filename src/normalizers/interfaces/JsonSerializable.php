<?php

namespace Json\Normalizer;

/**
 * Normalizes an instance of `JsonSerializable` for JSON serialization
 */
class JsonSerializable extends \Json\Normalizer
{
	public function jsonSerialize()
	{
		return static::prepare($this('data')->jsonSerialize(), $this('nested'));
	}
}
