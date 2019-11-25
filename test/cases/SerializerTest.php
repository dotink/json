<?php

use PHPUnit\Framework\TestCase;

/**
 *
 */
class SerializerTest extends TestCase
{
	public function testFull()
	{
		$container = new League\Container\Container();

		$container->delegate(new League\Container\ReflectionContainer());

		Json\Normalizer::setContainer($container);
		Json\Normalizer::setNamespace('Json\Normalizer');

		$result = '"2020-01-01T00:00:00+00:00"';

		$this->assertEquals($result, Json\Serialize(new DateTime('2020-01-01')));
		$this->assertNotEquals($result, json_encode(new DateTime('2020-01-01')));

		$this->assertEquals($result, Json\Serialize(new DateTimeWithSerialize('2020-01-01')));
		$this->assertEquals($result, json_encode(new DateTimeWithSerialize('2020-01-01')));

		$result = '"\\/tmp\\/example.txt"';

		$this->assertEquals($result, Json\Serialize(new SplFileInfo('/tmp/example.txt')));
		$this->assertNotEquals($result, json_encode(new SplFileInfo('/tmp/example.txt')));

		$this->assertEquals($result, Json\Serialize(new SplFileInfoWithSerialize('/tmp/example.txt')));
		$this->assertEquals($result, json_encode(new SplFileInfoWithSerialize('/tmp/example.txt')));

		$result = '{"publicArray":[],"publicInt":1,"publicString":"public"}';

		$this->assertEquals($result, Json\Serialize(new Acme()));
		$this->assertEquals($result, json_encode(new Acme()));

		$this->assertEquals($result, Json\Serialize(new AcmeWithSerialize()));
		$this->assertEquals($result, json_encode(new AcmeWithSerialize()));

		$result = '{"protectedString":"protected","privateString":"private"}';

		$this->assertEquals($result, Json\Serialize(new AcmeWithNormalizer()));
		$this->assertEquals($result, json_encode(new AcmeWithNormalizer()));

		$this->assertEquals($result, Json\Serialize(new AcmeWithNormalizerChild()));
		$this->assertEquals($result, json_encode(new AcmeWithNormalizerChild()));

		$result = '{"child":{"publicArray":[],"publicInt":1,"publicString":"public"},"publicArray":[],"publicInt":1,"publicString":"public"}';

		$this->assertEquals($result, Json\Serialize(new AcmeWithChild()));
		$this->assertEquals($result, json_encode(new AcmeWithChild()));

		$this->assertEquals($result, Json\Serialize(new AcmeWithSerializeWithChild()));
		$this->assertEquals($result, json_encode(new AcmeWithSerializeWithChild()));

		$result = '{"protectedString":"protected","privateString":"private","child":"nested"}';

		$this->assertEquals($result, Json\Serialize(new AcmeWithNormalizerWithChild()));
		$this->assertEquals($result, json_encode(new AcmeWithNormalizerWithChild()));
	}
}
