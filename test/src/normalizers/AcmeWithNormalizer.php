<?php

namespace Json\Normalizer;

class AcmeWithNormalizer extends \Json\Normalizer
{
	public function jsonSerialize()
	{
		if ($this('nested')) {
			return 'nested';

		} else {
			return [
				'protectedString' => $this->protectedString,
				'privateString'   => $this->getPrivateString()
			];
		}
	}
}
