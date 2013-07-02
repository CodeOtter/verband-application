<?php 

namespace Application;

use Verband\Framework\Structure\Package;

/**
 * 
 * @author Programmer
 *
 */
class Startup extends Package {

	/**
	 * 
	 */
	public function init($contexts) {
	}

	/**
	 * (non-PHPdoc)
	 * @see Verband\Framework\Structure.Package::getName()
	 */
	public function getName() {
		return 'Project\Application';
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Verband\Framework\Structure.Package::getNamespaces()
	 */
	public function getNamespaces($packagesPath) {
		return array(
			'Project\Application' => __DIR__ .'/{>1}'
		);
		
	}
}