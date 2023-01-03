<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
	public function index()
	{
		$this->loginView();
	}

	public function loginView()
	{
		$signup_view = $this->load->view('auth/login', [], true);
		$this->load->view('layout', ['content' => $signup_view]);
	}

	public function submit()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('error', 'Invalid email / password');
			$this->loginView();
		} else {
			if ($_POST['email'] == 'admin@gmail.com' && $_POST['password'] == '12345678') {
				$_SESSION['name'] = 'Admin';
				redirect('dashboard');
			} else {
				$this->session->set_flashdata('error', 'Invalid email / password');
				$this->loginView();
			}
		}
	}

	public function logout()
	{
		unset($_SESSION['name']);
		redirect('/login');
	}

}