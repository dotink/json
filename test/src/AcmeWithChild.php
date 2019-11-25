<?php

class AcmeWithChild extends Acme
{
	public $child = NULL;

	public function __construct()
	{
		$this->child = new Acme();
	}
}
