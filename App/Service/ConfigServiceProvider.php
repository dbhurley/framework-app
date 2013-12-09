<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Registry\Registry;

/**
 * Configuration service provider
 *
 * @since  1.0
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
	/**
	 * Configuration object
	 *
	 * @var    Registry
	 * @since  1.0
	 */
	protected $config;

	/**
	 * Class constructor.
	 *
	 * @param   string  $path  Path to the config file.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function __construct($path)
	{
		// Set the configuration file path for the application.
		$file = JPATH_CONFIGURATION . '/config.json';

		// Verify the configuration exists and is readable.
		if (!is_readable($file))
		{
			throw new \RuntimeException('Configuration file does not exist or is unreadable.');
		}

		// Load the configuration file into an object.
		$configObject = json_decode(file_get_contents($file));

		if ($configObject === null)
		{
			throw new \RuntimeException(sprintf('Unable to parse the configuration file %s.', $file));
		}

		$config = new Registry;

		$config->loadObject($configObject);

		$this->config = $config;
	}

	/**
	 * {@inheritdoc}
	 */
	public function register(Container $container)
	{
		$config = $this->config;

		$container->share(
			'config',
			function () use ($config)
			{
				return $config;
			}
		);
	}
}
