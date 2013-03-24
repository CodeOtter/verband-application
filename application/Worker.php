<?php

require(realpath(__DIR__ . '/../Packages/verband/framework/Core.php'));

// Initialize the Framework
$framework = new \Verband\Framework\Core();
$framework->init();
$framework->runWorker();