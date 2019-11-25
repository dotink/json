<?php

namespace Json\Normalizer;

class AcmeWithNormalizerWithChild extends AcmeWithNormalizer
{
	public function jsonSerialize()
	{
		return parent::jsonSerialize() + [
			'child' => $this->child
		];
	}
}
