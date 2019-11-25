<?php

namespace Json\Normalizer;

class AcmeWithNormalizer extends \Json\Normalizer
{
	public function jsonSerialize()
	{
		if ($this('nested')) {
			return static::prepare('nested');

		} else {
			return static::prepare([
				'protectedString' => $this->protectedString,
				'privateString'   => $this->getPrivateString()
			]);
		}
	}
}
