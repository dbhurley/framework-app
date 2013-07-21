<?php

/**
 *
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Router;

use Joomla\Application\AbstractApplication;
use Joomla\Controller\ControllerInterface;
use Joomla\Input\Input;
use Joomla\Router\Router;

use App\Router\Exception\RoutingException;


/**
 * Sample Router
 *
 * @since  1.0
 */
class AppRouter extends Router
{
	/**
	 * Application object to inject into controllers
	 *
	 * @var    AbstractApplication
	 * @since  1.0
	 */
	protected $app;

	/**
	 * Constructor.
	 *
	 * @param   Input                $input  An optional input object from which to derive the route.  If none
	 *                                       is given than the input from the application object will be used.
	 * @param   AbstractApplication  $app    An optional application object to inject to controllers
	 *
	 * @since   1.0
	 */
	public function __construct(Input $input = null, AbstractApplication $app = null)
	{
		parent::__construct($app->input);

		$this->app = $app;
	}

}
