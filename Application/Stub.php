<?php 

// Register the Framework class.
include __DIR__ . '/../verband.phar';

// Initialize the Framework
$framework = new \Framework\Core();
$framework->init();

$input = new \Symfony\Component\Console\Input\ArgvInput();

switch($input->getArgument('--as')) {
	case 'console':
		$framework->runConsole();
	break;
	
	case 'worker':
		$framework->runWorker();
	break;

	case 'instance':
	default:
		$framework->runWorkflow();
	break;
}

__HALT_COMPILER();