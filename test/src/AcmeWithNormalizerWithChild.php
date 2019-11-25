<?php

class AcmeWithNormalizerWithChild extends AcmeWithNormalizer
{
	private $child = NULL;

	public function __construct()
	{
		$this->child = new AcmeWithNormalizer();
	}
}
