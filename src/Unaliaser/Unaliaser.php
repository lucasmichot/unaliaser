<?php

/**
 * This file is part of the Unaliaser package.
 *
 * (c) Lucas Michot [lucas@semalead.com]
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unaliaser;

use InvalidArgumentException;

class Unaliaser {

	/**
	 * The input email.
	 *
	 * @var string
	 */
	public $email;

	/**
	 * Create a new Unaliaser instance.
	 *
	 * @param string $email
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct($email)
	{
		$this->email = $email;

		if (filter_var(trim(strtolower($email)), FILTER_VALIDATE_EMAIL) == false)
		{
			throw new InvalidArgumentException('Unaliaser must be constructed with a a valid email address.');
		}
	}

	/**
	 * Get the clean version of the email.
	 *
	 * @return string
	 */
	public function cleanEmail()
	{
		return trim(strtolower($this->email));
	}

	/**
	 * Get the email domain name
	 *
	 * @return string
	 */
	public function domainName()
	{
		return substr($this->cleanEmail(), strpos($this->cleanEmail(), '@') + 1);
	}

	/**
	 * Checks if the mail is from Gmail
	 *
	 * @return bool
	 */
	public function isGmail()
	{
		return $this->domainName() == 'gmail.com' or $this->domainName() == 'googlemail.com';
	}

	/**
	 * Get MX records for the email
	 *
	 * @return array
	 */
	public function mxRecords()
	{
		$mxRecords = array();
		dns_get_mx($this->domainName(), $mxRecords);

		return $mxRecords;
	}

	/**
	 * Checks if the email is from GoogleApps
	 *
	 * @return bool
	 */
	public function isGoogleApps()
	{
		if ($this->isGmail())
		{
			return false;
		}

		foreach ($this->mxRecords() as $mxRecord)
		{
			if (substr($mxRecord, - strlen('.googlemail.com')) === '.googlemail.com' or substr($mxRecord, - strlen('.google.com')) === '.google.com')
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks if the email is handled by Google
	 *
	 * @return bool
	 */
	public function isGoogle()
	{
		return $this->isGoogleApps() or $this->isGmail();
	}

	/**
	 * Get a unique domain name for the email.
	 *
	 * @return string
	 */
	public function uniqueDomainName()
	{
		if ($this->isGmail())
		{
			return 'gmail.com';
		}

		return $this->domainName();
	}


	/**
	 * Get the email user name
	 *
	 * @return string
	 */
	public function userName()
	{
		return substr($this->cleanEmail(), 0, strpos($this->cleanEmail(), '@'));
	}

	/**
	 * Get the email user alias part
	 *
	 * @return string
	 */
	public function userAlias()
	{
		$pos = strpos($this->userName(), '+');
		if ($pos < 1 or $this->isGoogle() == false)
		{
			return null;
		}

		return substr($this->userName(), $pos + 1);
	}

	/**
	 * Checks if the email contains an alias
	 *
	 * @return bool
	 */
	public function hasUserAlias()
	{
		return $this->userAlias() !== null;
	}

	/**
	 * Get the email user origin part
	 *
	 * @return string
	 */
	public function userOrigin()
	{
		$pos = strpos($this->userName(), '+');
		if ($pos < 1 or $this->isGoogle() == false)
		{
			return $this->userName();
		}

		return substr($this->userName(), 0, $pos);
	}

	/**
	 * Get the email user undotted origin part
	 *
	 * @return string
	 */
	public function userUndottedOrigin()
	{
		if ($this->isGoogle() == false)
		{
			return $this->userName();
		}

		return str_replace('.', null, $this->userOrigin());
	}

	/**
	 * Checks if a user origin is dotted.
	 *
	 * @return bool
	 */
	public function userIsDotted()
	{
		return $this->userUndottedOrigin() !== $this->userOrigin();
	}

	/**
	 * Get the unique deduplicated and undotted email.
	 *
	 * @return string
	 */
	public function unique()
	{
		if ($this->isGoogle() == false)
		{
			return $this->cleanEmail();
		}

		return $this->userUndottedOrigin().'@'.$this->uniqueDomainName();
	}

	/**
	 * Magic __toString() method.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->unique();
	}

	/**
	 * Checks if the email us unique.
	 *
	 * @return string
	 */
	public function isUnique()
	{
		return $this->unique() == $this->cleanEmail();
	}
}