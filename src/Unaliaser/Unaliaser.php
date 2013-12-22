<?php

namespace Unaliaser;

class Unaliaser {

	/**
	 * The input email.
	 *
	 * @var string
	 */
	public $email;

	/**
	 * Create a new Unaliaser.
	 *
	 * @param string $email
	 */
	public function __construct($email)
	{
		$this->email = $email;
	}

	/**
	 * Get the clean version of the email.
	 *
	 * @return string
	 */
	public function cleanEmail()
	{
		return strtolower(trim($this->email));
	}

	/**
	 * Check if the input email is clean.
	 *
	 * @return bool
	 */
	public function isClean()
	{
		return $this->email === $this->cleanEmail();
	}

	/**
	 * Check if the email is handled by GoogleMail
	 *
	 * @return bool
	 */
	public function isGoogleMail()
	{
		return stripos('@gmail.com', $this->email) || stripos('@googlemail.com', $this->email);
	}

	/**
	 * Check if the email is handled by GoogleApps
	 *
	 * @return bool
	 */
	public function isGoogleApps()
	{
		return in_array($this->getMxRecords(), 'gmail.com');
	}

	/**
	 * Get email domain name
	 *
	 * @return string
	 */
	public function getDomainName()
	{
		$parts = explode('@', $this->cleanEmail());

		return $parts[0];
	}

	/**
	 * Get email username
	 *
	 * @return string
	 */
	public function getUsername()
	{
		$parts = explode('@', $this->cleanEmail());

		return $parts[1];
	}

	/**
	 * Get email domain name MX records
	 *
	 * @return array
	 */
	public function getMxRecords()
	{
		$mxRecords = array();
		dns_get_mx($this->getDomainName(), $mxRecords);

		return $mxRecords;
	}

	/**
	 * @return string
	 */
	public function uniqueEmail()
	{
		return $this->getUsername().'@'.$this->getDomainName();
	}


}