<?php

// Register the Framework class (compiled or source)
$rootDir = realpath(__DIR__ . '/../');

if(file_exists($rootDir . '/Application/Cache/verband.php')) {
	// Load the PHAR
	require($rootDir . '/Application/Cache/verband.php');
} else {
	// Load the source
	require($rootDir . '/Framework/Core.php');
}

// Initialize the Framework
$framework = new \Framework\Core();
$framework->init();
$framework->runConsole();