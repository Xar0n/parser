<?php
spl_autoload_register(function (string $class) {
	require __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
});