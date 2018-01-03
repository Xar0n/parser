<?php

namespace App;


class Curl
{
	protected $ch;
	protected $host;

	public static function init(string $host)
	{
		return new static($host);
	}

	private function __construct(string $host)
	{
		$this->ch = curl_init();
		$this->host = $host;
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
	}

	public function __destruct()
	{
		curl_close($this->ch);
	}

	public function set(int $option, mixed $value)
	{
		curl_setopt($this->ch, $option, $value);
		return $this;
	}

	/**
	 * @param int $value
	 * Test
	 */
	public function ssl(int $value = 0)
	{
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $value);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, $value);
	}

	public function request(string $url)
	{
		curl_setopt($this->ch, CURLOPT_URL, $this->make_url($url));
		$data = curl_exec($this->ch);
		return $this->process_result($data);
	}

	public function config_load()
	{

	}

	public function config_save($file)
	{

	}

	private function make_url(string $url)
	{
		if ('/' != $url[0]) {
			$url = '/' . $url;
		}
		return $this->host . $url;
	}

	private function process_result(string $data)
	{
		$p_n = "\n";
		$p_rn = "\r\n";
		$h_end_n = strpos($data, $p_n . $p_n);    // int - false
		$h_end_rn = strpos($data, $p_rn . $p_rn); // int - false
		$start = $h_end_n; // h_end_n int
		$p = $p_n;		 // \n

		if ($h_end_n === false || $h_end_rn < $h_end_n) {
			$start = $h_end_rn;
			$p = $p_rn;
		}

		$headers_part = substr($data, 0, $start);
		$body_part = substr($data, $start + strlen($p) * 2);
		$lines = explode($p,$headers_part);
		$headers = [];
		$headers['start'] = array_shift($lines);

		foreach ($lines as $v) {
			$del_pos = strpos($v, ':');
			$name = substr($v, 0, $del_pos);
			$value = substr($v, $del_pos + 2);
			$headers[$name] = $value;
		}

		return [
			'headers' => $headers,
			'html' => $body_part
		];
	}
}