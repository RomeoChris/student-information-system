<?php

namespace App\Core\Sanitize;


class Sanitize
{
	public static function input(string $data = '') :string
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
}
