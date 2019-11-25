<?php

class AcmeWithSerializeWithChild extends AcmeWithSerialize
{
	public $child = NULL;

	public function __construct()
	{
		$this->child = new AcmeWithSerialize();
	}
}
