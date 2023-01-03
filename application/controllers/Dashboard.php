<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$html = $this->load->view('dashboard', [], true);
		$this->load->view('layout', ['content' => $html]);
	}
}