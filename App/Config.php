<?php

namespace App;


class Config
{
	public $data = [];
	private static $instance =  null;

	public static function getInstance(string $file)
	{
		if (null === static::$instance) {
			static::$instance = new static($file);
		}
		return static::$instance;
	}

	private function __construct(string $file)
	{
		$this->data = include __DIR__ . '/../configs/' . $file;
	}

	private function __clone()
	{

	}
}