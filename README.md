Verband
=======

Verband is Dutch for "Context". This framework allows for the dynamic assembly of its process flow via application-defined contexts.

Setup (Ubuntu)
--------------

```
sudo vim /etc/apache2/sites-available/default

<Directory /var/www>
	AllowOverride All
</Directory>
```

```
sudo vim /etc/apache2/sites-available/verband.com

<VirtualHost *:80>
     ServerAdmin webmaster@verband.com
     ServerName verband.com
     ServerAlias www.verband.com
     DocumentRoot /var/www/verband.com/
     ErrorLog /var/log/apache2/verband.com.error.log
     CustomLog /var/log/verband.com.access.log combined
</VirtualHost>
```

```
sudo a2enmod rewrite
sudo a2ensite verband.com
sudo /etc/init.d/apache2 restart
```

```
sudo find /var/www/<location of your verband checkout> -type f -exec chmod 664 {} \;
sudo find /var/www/<location of your verband checkout> -type d -exec chmod 775 {} \;
sudo find /var/www/<location of your verband checkout> -type d -exec chmod g+s {} \;
echo "phar.readonly=0" | sudo tee -a /etc/php5/conf.d/phar.ini
```

```
git submodule update --init
cd Application/Doctrine/Core
git submodule update --init
```

Quick and Dirty
---------------

```php
<?php

namespace Application\BoringMVCFramework;

use Libraries\Core\Application;
use Libraries\Core\Context;
use Libraries\Core\Process;
use Application\BoringMVCFramework\CheckRequest;
use Application\BoringMVCFramework\GetUser;
use Application\BoringMVCFramework\InvokeController;
use Application\BoringMVCFramework\GenerateView;
use Application\BoringMVCFramework\Output;

class Startup extends Application {

	public function init() {}

	public function assembleContext($contexts) {
		$contexts->findChildByNodeName('framework.init')
			->addChild(Context::stitch(array(
				new Context('Check Request',						$this, new CheckRequest()),
				new Context('Use the session and fetch the user',	$this, new GetUser()),
				new Context('Route the Request to a controller',	$this, new InvokeController()),
				new Context('Generate a view',						$this, new GenerateView()),
				new Context('Spit that shit out',					$this, new Output()),
			)
		));

		return $contexts;
	}
}

```

There.  I have represented the implied ontology of all PHP frameworks in ten actual lines of code.

What the hell just happened?
----------------------------

Once upon a time, there was a framework for PHP.  And it was good.

Web developers eschewed all other programming patterns and embraced the ORM/MVC/RPC paradigm.  And it was good.

From this, they created thirty more frameworks, each one a slight variation of a previous ontology.  The goodness grew cold.

Web developers pushed this paradigm to the extreme and began doing straight-up code lifts from Ruby and Java.  Then the goodness stopped.

Today, the Framework of Babel reigns supreme in PHP Land.  Each enclave claims their ontology is the best.  This argument is pointless because it is subjective.

Verband doesn't care about your ontology's prowess.  Verband lets you assemble ontology dynamically at run-time.

You're a chatty bitch.  The hell is an ontology?
------------------------------------------------

_"In theory, an ontology is a "formal, explicit specification of a shared conceptualization". An ontology renders shared vocabulary and taxonomy which models a domain with the definition of objects and/or concepts and their properties and relations" ~[The Wiks](http://en.wikipedia.org/wiki/Ontology_(information_science))_

Every single framework in existence implies their ontology, not from the code base or the patterns they choose, but by assuming a context where it is the center of the universe.  Even the most religious adherence to functional programming cannot ensure an absolute separation of concern between all components of a framework.  As a result, trying to tinker with a framework's process flow to be more reflective of project requirements becomes very problematic.

The ontology of all frameworks assumes a static process flow. (input -> processing -> output)  To get into the space in between processes (where project requirements constantly find themselves wanting to be), things like event listeners and hooks are utilized, but even these are bound to an unresponsive ontology.  At best, they simulate dynamic ontology in a fixed manner.

Verband allows a developer to define application-specific ontologies by chaining together custom contexts.  This liberates the framework from having a fixed ontology, allowing the context of a project to be represented in a functional, reusable, and dynamic way.

Okay, Mr. Philosopy.  Show me the good stuff.
---------------------------------------------

Let's assume the ontology of our framework defines the process flow to be the following:

```
Framework Initialize->
 Request Initialize->
  Request Routing->
   Request Formatting->
    Request Mapping->
     Controller Execution->
      Response Generation->
       Response Mapping->
        Response Formatting->
         Response Dispatch
```

**Q: How do you traditionally represent this in a framework?**

**A: Spaghetti style.**

The short answer is you can't.  You can only imply the process flow at a conceptual level while enforcing it via scattered invocations of methods and classes throughout your project.

But with Verband, you define the process flow upfront:


```php
<?php

namespace Application\Rest;

use Libraries\Core\Application;
use Libraries\Core\Context;
use Libraries\Core\Process;
use Application\Rest\Processes\Formatter;
use Application\Rest\Processes\TypeMap\Mapper;
use Application\Rest\Processes\Router;
use Application\Rest\Processes\Request;
use Application\Rest\Processes\Response;

class Startup extends Application {

	public function init() {}

	public function assembleContext($contexts) {
		$contexts->findChildByNodeName('framework.init')
			->addChild(Context::stitch(array(
				new Context('request.generation',	$this, new Request()),
				new Context('request.routing',		$this, new Router()),
				new Context('request.format',		$this, new Formatter()),
				new Context('request.mapping',		$this, new Mapper()),
				new Context('controller.execution',	$this, function($context, $lastResult) {
					return array(
						'result' => new \Application\Rest\Entities\Test(),
						'status'=> 200
					);
				}),
				new Context('response.generation',	$this, new Response()),
				new Context('response.mapping',		$this, new Mapper()),
				new Context('response.format',		$this, new Formatter()),
				new Context('response.dispatch',	$this, function($context, $lastResult) {
					$lastResult->send();
				})
			)
		));

		return $contexts;
	}
}

```

Let me translate that into English:


```
Let us utilize the namespace for a custom application.

Let us define the custom application.
	
Let us define the assembleContext process as follows:
	In the master list of contexts, find the context for the Framework Initialization,
	then add the following list of stitched contexts (Converts a array of contexts into a tree of contexts)
	to it as children:
		'Request Generation', which will utilize the Request() process.
		'Request Routing', which will utilize the Router() process.
		'Request Format', which will utilize the Formatter() process.
		'Request Mapping', which will utilize the Mapper() process.
		'Controller Execution' which will utilize a closure process.
		'Response Generation', which will utilize the Response() process.
		'Response Mapping', which will utilize the Mapper() process.
		'Response Formatting', which will utilize the Formatter() process.
		'Response Dispatch' which will utilize a closure process.

```
If I was to make a call to ```$context->traceHtml()``` in the Response Dispatch process, I would get the following tree of Contexts:

```
framework.init : Application\Core\Startup.php
  request.generation : Application\Rest\Processes\Request.php
    request.format : Application\Rest\Processes\Formatter.php
      request.mapping : Application\Rest\Processes\TypeMap\Mapper.php
        request.routing : Application\Rest\Processes\Router.php
          controller.execute : Application\Rest\Startup.php
            response.mapping : Application\Rest\Processes\TypeMap\Mapper.php
              response.format : Application\Rest\Processes\Formatter.php
                response.generation : Application\Rest\Processes\Response.php
                  response.dispatch : Application\Rest\Startup.php
```

The Framework is now aware of its process flow, where each Context is defined.  It even knows what context an Exception is thrown from.

If you notice, the 'framework.init' Context is defined in the Core Application while everything else is defined in the Rest Application.  The ontology of the Project has adapted to become a mixture of Contexts between two different Applications!

General rules about contexts
----------------------------

* Each Context Process is passed the current context of the workflow.
* Aditionally, each Context Process is given the output of the previous Context Process. (Think monads)
* A Context can register the state of a value to itself with ```setState($key, $value)```, which can be accessed by its children via ```getState($key)```.  For example, to access the Framework singleton from any context, simply call ```$context->getState('framework')```.
* A Project can have many Applications, and each Application defines its own Contexts. 