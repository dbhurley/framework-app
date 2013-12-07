<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App;

use App\Service\ApplicationServiceProvider;
use Joomla\Application\AbstractWebApplication;
use Joomla\Controller\ControllerInterface;
use Joomla\DI\Container;
use Joomla\DI\ContainerAwareInterface;

use App\Router\AppRouter;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Application class
 *
 * @since  1.0
 */
final class App extends AbstractWebApplication implements ContainerAwareInterface
{
	/**
	 * DI Container
	 *
	 * @var    Container
	 * @since  1.0
	 */
	protected $container;

	/**
	 * The default theme.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $theme = null;

	/**
	 * A session object.
	 *
	 * @var    Session
	 * @since  1.0
	 * @note   This has been created to avoid a conflict with the $session member var from the parent class.
	 */
	private $newSession = null;

	/**
	 * Class constructor.
	 *
	 * @since   1.0
	 */
	public function __construct(Container $container)
	{
		// Run the parent constructor
		parent::__construct();

		$this->setContainer($container);

		// Merge the config into the application
		$config = $container->get('config');
		$this->config->merge($config);

		$this->theme = $this->config->get('theme.default');

		define('BASE_URL', $this->get('uri.base.full'));
		define('DEFAULT_THEME', BASE_URL . 'themes/' . $this->theme);
	}

	/**
	 * Method to run the Web application routines.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function doExecute()
	{
		try
		{
			// Instantiate the router
			$router = new AppRouter($this->input, $this);
			$maps = json_decode(file_get_contents(JPATH_CONFIGURATION . '/routes.json'));

			if (!$maps)
			{
				throw new \RuntimeException('Invalid router file.', 500);
			}

			$router->addMaps($maps, true);
			$router->setControllerPrefix('\\App');
			$router->setDefaultController('\\Controller\\DefaultController');

			// Fetch the controller
			/* @type ControllerInterface $controller */
			$controller = $router->getController($this->get('uri.route'));
			$content = $controller->execute();
		}
		catch (\Exception $exception)
		{
			header('HTTP/1.1 500 Internal Server Error', true, 500);

			$content = $exception->getMessage();
		}

		$this->setBody($content);
	}

	/**
	 * Enqueue a system message.
	 *
	 * @param   string  $msg   The message to enqueue.
	 * @param   string  $type  The message type. Default is message.
	 *
	 * @return  $this  Method allows chaining
	 *
	 * @since   1.0
	 */
	public function enqueueMessage($msg, $type = 'message')
	{
		$this->getSession()->getFlashBag()->add($type, $msg);

		return $this;
	}

	/**
	 * Get the DI container.
	 *
	 * @return  Container
	 *
	 * @since   1.0
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * Get a session object.
	 *
	 * @return  Session
	 *
	 * @since   1.0
	 */
	public function getSession()
	{
		if (is_null($this->newSession))
		{
			$this->newSession = new Session;
			$this->newSession->start();
		}

		return $this->newSession;
	}

	/**
	 * Clear the system message queue.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function clearMessageQueue()
	{
		$this->getSession()->getFlashBag()->clear();
	}

	/**
	 * Get the system message queue.
	 *
	 * @return  array  The system message queue.
	 *
	 * @since   1.0
	 */
	public function getMessageQueue()
	{
		return $this->getSession()->getFlashBag()->peekAll();
	}

	/**
	 * Set the DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  $this  Method allows chaining
	 *
	 * @since   1.0
	 */
	public function setContainer(Container $container)
	{
		$this->container = $container;

		return $this;
	}

	/**
	 * Set the system message queue for a given type.
	 *
	 * @param   string  $type     The type of message to set
	 * @param   mixed   $message  Either a single message or an array of messages
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setMessageQueue($type, $message = '')
	{
		$this->getSession()->getFlashBag()->set($type, $message);
	}
}
