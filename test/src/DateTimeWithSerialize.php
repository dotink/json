<?php

class DateTimeWithSerialize extends DateTime implements Json\Serializable
{
	use Json\Serialize;
}
