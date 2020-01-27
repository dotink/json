<?php

namespace Json\Normalizer;

/**
 * Normalizes a PHP `SplFileInfo` object for JSON serialization
 */
class SplFileInfo extends \Json\Normalizer
{
	public function jsonSerialize()
	{
		if ($this('nested')) {
			return static::prepare($this->getPathname());
		} else {
			return static::prepare([
				'pathname' => $this->getPathname(),
				'filename' => $this->getFilename(),
				'size'     => $this->getSize()
			]);
		}
	}
}
