<?php

namespace Json;

/**
 * Serialize data to JSON, ensuring it is normalized first.
 *
 * @var mixed $data The data to normalize and serialize
 * @return string A JSON representation of the normalized data
 */
function Serialize($data, int $options = 0, int $depth = 512)
{
	return json_encode(Normalizer::prepare($data, FALSE), $options, $depth);
}
