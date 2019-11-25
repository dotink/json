<?php

namespace Json\Normalizer;

/**
 * Normalizes a PHP `SplFileInfo` object for JSON serialization
 */
class SplFileInfo extends \Json\Normalizer
{
	public function jsonSerialize()
	{
		return $this->getPathname();
	}
}
