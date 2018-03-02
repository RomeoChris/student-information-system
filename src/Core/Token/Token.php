<?php

namespace App\Core\Token;


use App\Core\Session\AppSession;

class Token
{
	private $session;

	public function __construct(AppSession $session)
	{
		$this->session = $session;
	}

	public function generate(string $tokenName) :string
	{
		$token = hash('md5', uniqid());
		$this->session->set($tokenName, $token);
		return $token;
	}

	public function validate(string $tokenName, string $token = '') :bool
	{
		if ($this->session->has($tokenName) && $token == $this->session->get($tokenName))
		{
		    $this->session->remove($tokenName);
			return true;
		}
		return false;
	}
}
