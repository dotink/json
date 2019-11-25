<?php

class Acme
{
	public $publicArray = [];

	public $publicInt = 1;

	public $publicString = 'public';

	protected $protectedString = 'protected';

	private $privateString = 'private';

	protected function getPrivateString()
	{
		return $this->privateString;
	}
}
