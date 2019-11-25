<?php

namespace Json\Normalizer;

class AcmeWithNormalizer extends \Json\Normalizer
{
	public function jsonSerialize()
	{
		return [
			'protectedString' => $this->protectedString,
			'privateString'   => $this->getPrivateString()
		];
	}
}
