<?php

namespace Json\Normalizer;

class AcmeWithNormalizerWithChild extends AcmeWithNormalizer
{
	public function jsonSerialize()
	{
		return static::prepare(parent::jsonSerialize()('data') + [
			'child' => $this->child
		]);
	}
}
