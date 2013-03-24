<?php

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class Installer extends LibraryInstaller
{
    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
	$extras = $package->getExtra();
	if($extra && isset($extra['component-path'])) {
		return $extra['component-path'];
	}
	$name = explode('/', $package->getPrettyName());
	return 'Packages/' . ucfirst($name[0]) . '/' . ucfirst($name[1]);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return 'verband-component' === $packageType;
    }
}
