<?php

require(realpath(__DIR__ . '/../packages/verband/framework/Core.php'));

$framework = new \Verband\Framework\Core();
$framework->init();

$input = new \Symfony\Component\Console\Input\ArgvInput();

$site = $input->getFirstArgument();
if(!$site) {
	die("\n".'You have not specified a site to deploy to.'."\n\n");	
}

$environment = $input->getParameterOption('--env', 'www');

echo "\n".'Deploying...'."\n";

$output = array();
exec("rsync -pcrlzvi -e 'ssh -p 5546' --chmod=u=rwx,g=rx --filter='merge application/Settings/deploy.filter' . $site@$environment.$site.com:/home/$site/public_html/", $output);
echo implode("\n", $output)."\n";

/*
$output = array();
exec("ssh -p 5546 $site@$environment.$site.com 'chown -R $1:www-data public_html'", $output);
echo implode("\n", $output)."\n";
*/
echo "\n".'Done!'."\n";