<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function run()
	{
		// $html = $this->load->view('dashboard', [], true);
		// $this->load->view('layout', ['content' => $html]);

		$response = doCurl(
			'https://rest.coinapi.io/v1/exchangerate/BTC/USD/history?period_id=1HRS&time_start=2022-12-01T00:00:00&time_end=2022-12-31T00:00:00&limit=100000',
			[],
			true,
			null,
			[
				'X-CoinAPI-Key' => COIN_API_KEY,
				'Content-Type' => 'application/x-www-form-urlencoded'
			]
		);

		foreach ($response as $row) {
			$this->db->insert('price', [
				'period' => $row['time_period_end'],
				'price' => $row['rate_close']
			]);
		}
	}
}