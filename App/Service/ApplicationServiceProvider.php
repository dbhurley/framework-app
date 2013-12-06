<?php
/**
 * Part of the Joomla Tracker Service Package
 *
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Service;

use App\App;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * Application service provider
 *
 * @since  1.0
 */
class ApplicationServiceProvider implements ServiceProviderInterface
{
	/**
	 * Application instance
	 *
	 * @var    App
	 * @since  1.0
	 */
	private $app;

	/**
	 * Constructor
	 *
	 * @param   App  $app  Application instance
	 *
	 * @since   1.0
	 */
	public function __construct(App $app)
	{
		$this->app = $app;
	}

	/**
	 * {@inheritdoc}
	 */
	public function register(Container $container)
	{
		$app = $this->app;

		$container->set('App\\App',
			function () use ($app)
			{
				return $app;
			}, true, true
		);

		// Alias the application
		$container->alias('app', 'App\\App');
	}
}
