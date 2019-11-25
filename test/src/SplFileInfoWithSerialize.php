<?php

class SplFileInfoWithSerialize extends SplFileInfo implements Json\Serializable
{
	use Json\Serialize;
}
