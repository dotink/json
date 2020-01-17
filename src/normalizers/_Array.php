<?php

namespace Json\Normalizer;

/**
 * Normalizes a PHP `(array)` for JSON serialization
 */
class _Array extends \Json\Normalizer
{
	public function jsonSerialize()
	{
		return array_map(
			function($item) {
				//
				// Prepare the items of the array with our own nesting level (if we're root level,
				// treat the items as root level).
				//

				return static::prepare($item, $this('nested'));
			},
			$this('data')
		);
	}
}
