<?php

class AcmeWithSerialize extends Acme implements Json\Serializable
{
	use Json\Serialize;
}
