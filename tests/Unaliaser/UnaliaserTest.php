<?php

/**
 * This file is part of the Unaliaser package.
 *
 * (c) Lucas Michot [lucas@semalead.com]
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Unaliaser\Unaliaser;

class UnaliaserTest extends PHPUnit_Framework_TestCase {

	public function testConstruct()
	{
		$unaliaser = new Unaliaser('FOO@BAR.COM');
		$this->assertTrue($unaliaser instanceof Unaliaser);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testConstructArgumentStringNotEmail()
	{
		$unaliaser = new Unaliaser('@BAR.COM');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testConstructArgumentNotString()
	{
		$unaliaser = new Unaliaser(123);
	}


	public function testCleanEmail()
	{
		$unaliaser = new Unaliaser('    FOO@BAR.COM     ');
		$this->assertEquals('foo@bar.com', $unaliaser->cleanEmail());

		$unaliaser = new Unaliaser(PHP_EOL.'FOO@BAR.COM'.PHP_EOL);
		$this->assertEquals('foo@bar.com', $unaliaser->cleanEmail());

		$unaliaser = new Unaliaser('foo@bar.com');
		$this->assertEquals('foo@bar.com', $unaliaser->cleanEmail());
	}


	public function testDomainName()
	{
		$unaliaser = new Unaliaser('foo@bar.com');
		$this->assertEquals('bar.com', $unaliaser->domainName());
	}


	public function testIsGmail()
	{
		$unaliaser = new Unaliaser('foo@bar.com');
		$this->assertFalse($unaliaser->isGmail());

		$unaliaser = new Unaliaser('foo@gmail.com');
		$this->assertTrue($unaliaser->isGmail());

		$unaliaser = new Unaliaser('foo@googlemail.com');
		$this->assertTrue($unaliaser->isGmail());
	}


	public function testMxRecords()
	{
		$unaliaser = new Unaliaser('foo@gmail.com');
		$this->assertNotEmpty($unaliaser->mxRecords());

		$unaliaser = new Unaliaser('lucas@semalead.com');
		$this->assertNotEmpty($unaliaser->mxRecords());
	}


	public function testIsGoogleApps()
	{
		$unaliaser = new Unaliaser('foo@bar.com');
		$this->assertFalse($unaliaser->isGoogleApps());

		$unaliaser = new Unaliaser('foo@gmail.com');
		$this->assertFalse($unaliaser->isGoogleApps());

		$unaliaser = new Unaliaser('lucas@semalead.com');
		$this->assertTrue($unaliaser->isGoogleApps());
	}


	public function testIsGoogle()
	{
		$unaliaser = new Unaliaser('foo@bar.com');
		$this->assertFalse($unaliaser->isGoogle());

		$unaliaser = new Unaliaser('foo@gmail.com');
		$this->assertTrue($unaliaser->isGoogle());

		$unaliaser = new Unaliaser('lucas@semalead.com');
		$this->assertTrue($unaliaser->isGoogle());
	}


	public function testUniqueDomainName()
	{
		$unaliaser = new Unaliaser('foo@bar.com');
		$this->assertEquals('bar.com', $unaliaser->uniqueDomainName());

		$unaliaser = new Unaliaser('foo@gmail.com');
		$this->assertEquals('gmail.com', $unaliaser->uniqueDomainName());

		$unaliaser = new Unaliaser('foo@googlemail.com');
		$this->assertEquals('gmail.com', $unaliaser->uniqueDomainName());

		$unaliaser = new Unaliaser('lucas@semalead.com');
		$this->assertEquals('semalead.com', $unaliaser->uniqueDomainName());
	}


	public function testUserName()
	{
		$unaliaser = new Unaliaser('lucas@semalead.com');
		$this->assertEquals('lucas', $unaliaser->userName());

		$unaliaser = new Unaliaser('lu.cas@semalead.com');
		$this->assertEquals('lu.cas', $unaliaser->userName());

		$unaliaser = new Unaliaser('lucas+alias@semalead.com');
		$this->assertEquals('lucas+alias', $unaliaser->userName());

		$unaliaser = new Unaliaser('lu.cas+alias@semalead.com');
		$this->assertEquals('lu.cas+alias', $unaliaser->userName());
	}


	public function testUserAlias()
	{
		$unaliaser = new Unaliaser('lucas@semalead.com');
		$this->assertNull($unaliaser->userAlias());

		$unaliaser = new Unaliaser('lu.cas@semalead.com');
		$this->assertNull($unaliaser->userAlias());

		$unaliaser = new Unaliaser('lucas+alias@semalead.com');
		$this->assertEquals('alias', $unaliaser->userAlias());

		$unaliaser = new Unaliaser('lu.cas+alias@semalead.com');
		$this->assertEquals('alias', $unaliaser->userAlias());

		$unaliaser = new Unaliaser('foo+alias@bar.com');
		$this->assertNull($unaliaser->userAlias());
	}


	public function testHasUserAlias()
	{
		$unaliaser = new Unaliaser('lucas@semalead.com');
		$this->assertFalse($unaliaser->hasUserAlias());

		$unaliaser = new Unaliaser('lu.cas@semalead.com');
		$this->assertFalse($unaliaser->hasUserAlias());

		$unaliaser = new Unaliaser('lucas+alias@semalead.com');
		$this->assertTrue($unaliaser->hasUserAlias());

		$unaliaser = new Unaliaser('lu.cas+alias@semalead.com');
		$this->assertTrue($unaliaser->hasUserAlias());

		$unaliaser = new Unaliaser('foo+alias@bar.com');
		$this->assertFalse($unaliaser->hasUserAlias());
	}


	public function testUserOrigin()
	{
		$unaliaser = new Unaliaser('lucas@semalead.com');
		$this->assertEquals('lucas', $unaliaser->userOrigin());

		$unaliaser = new Unaliaser('lu.cas@semalead.com');
		$this->assertEquals('lu.cas', $unaliaser->userOrigin());

		$unaliaser = new Unaliaser('lucas+alias@semalead.com');
		$this->assertEquals('lucas', $unaliaser->userOrigin());

		$unaliaser = new Unaliaser('lu.cas+alias@semalead.com');
		$this->assertEquals('lu.cas', $unaliaser->userOrigin());

		$unaliaser = new Unaliaser('foo+alias@bar.com');
		$this->assertEquals('foo+alias', $unaliaser->userOrigin());
	}


	public function testUserUndottedOrigin()
	{
		$unaliaser = new Unaliaser('lucas@semalead.com');
		$this->assertEquals('lucas', $unaliaser->userUndottedOrigin());

		$unaliaser = new Unaliaser('lu.cas@semalead.com');
		$this->assertEquals('lucas', $unaliaser->userUndottedOrigin());

		$unaliaser = new Unaliaser('lucas+alias@semalead.com');
		$this->assertEquals('lucas', $unaliaser->userUndottedOrigin());

		$unaliaser = new Unaliaser('lu.cas+alias@semalead.com');
		$this->assertEquals('lucas', $unaliaser->userUndottedOrigin());

		$unaliaser = new Unaliaser('foo+alias@bar.com');
		$this->assertEquals('foo+alias', $unaliaser->userUndottedOrigin());
	}


	public function testUserIsDottedOrigin()
	{
		$unaliaser = new Unaliaser('lucas@semalead.com');
		$this->assertFalse($unaliaser->userIsDotted());

		$unaliaser = new Unaliaser('lu.cas@semalead.com');
		$this->assertTrue($unaliaser->userIsDotted());

		$unaliaser = new Unaliaser('lucas+alias@semalead.com');
		$this->assertFalse($unaliaser->userIsDotted());

		$unaliaser = new Unaliaser('foo@gmail.com');
		$this->assertFalse($unaliaser->userIsDotted());

		$unaliaser = new Unaliaser('fo.o@gmail.com');
		$this->assertTrue($unaliaser->userIsDotted());

		$unaliaser = new Unaliaser('foo+alias@bar.com');
		$this->assertFalse($unaliaser->userIsDotted());
	}


	/**
	 * Data providers for testUnique
	 *
	 * @return array
	 */
	public static function dataProviderUnique()
	{
		return array(
			array('foo@bar.com', 'foo@bar.com'),
			array('foo@gmail.com', 'foo@gmail.com'),
			array('foo@googlemail.com', 'foo@gmail.com'),
			array('foo@semalead.com', 'foo@semalead.com'),
			array('f.o.o@bar.com', 'f.o.o@bar.com'),
			array('f.o.o@gmail.com', 'foo@gmail.com'),
			array('f.o.o@googlemail.com', 'foo@gmail.com'),
			array('f.o.o@semalead.com', 'foo@semalead.com'),
			array('foo+123@bar.com', 'foo+123@bar.com'),
			array('foo+123@gmail.com', 'foo@gmail.com'),
			array('foo+123@googlemail.com', 'foo@gmail.com'),
			array('foo+123@semalead.com', 'foo@semalead.com'),
			array('f.o.o+123@bar.com', 'f.o.o+123@bar.com'),
			array('f.o.o+123@gmail.com', 'foo@gmail.com'),
			array('f.o.o+123@googlemail.com', 'foo@gmail.com'),
			array('f.o.o+123@semalead.com', 'foo@semalead.com'),
		);
	}

	/**
	 * @dataProvider dataProviderUnique
	 */
	public function testUnique($email, $uniqueEmail)
	{
		$unaliaser = new Unaliaser($email);
		$this->assertEquals($uniqueEmail, $unaliaser->unique());
	}

	/**
	 * Data providers for testIsUnique
	 *
	 * @return array
	 */
	public static function dataProviderIsUnique()
	{
		return array(
			array('foo@bar.com', true),
			array('foo@gmail.com', true),
			array('foo@googlemail.com', false),
			array('foo@semalead.com', true),
			array('f.o.o@bar.com', true),
			array('f.o.o@gmail.com', false),
			array('f.o.o@googlemail.com', false),
			array('f.o.o@semalead.com', false),
			array('foo+123@bar.com', true),
			array('foo+123@gmail.com', false),
			array('foo+123@googlemail.com', false),
			array('foo+123@semalead.com', false),
			array('f.o.o+123@bar.com', true),
			array('f.o.o+123@gmail.com', false),
			array('f.o.o+123@googlemail.com', false),
			array('f.o.o+123@semalead.com', false),
		);
	}

	/**
	 * @dataProvider dataProviderIsUnique
	 */
	public function testIsUnique($email, $isUniqueEmail)
	{
		$unaliaser = new Unaliaser($email);
		if ($isUniqueEmail === true)
		{
			$this->assertTrue($unaliaser->isUnique());
		}
		elseif ($isUniqueEmail === false)
		{
			$this->assertFalse($unaliaser->isUnique());
		}
	}


}
