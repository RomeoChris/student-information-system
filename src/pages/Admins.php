<?php

class Admins extends Controller
{
	public function index()
	{
		requireAuth();
		$adminResults = Database::getAll('admins');
		require_once HEADER;
		require_once $this->view('admins');
		require_once FOOTER;
	}
}