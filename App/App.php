<?php
/**
 *
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App;

use Joomla\Application\AbstractWebApplication;
use Joomla\Controller\ControllerInterface;
use Joomla\Database\DatabaseDriver;
use Joomla\Event\Dispatcher;
use Joomla\Factory;
use Joomla\Github\Github;
use Joomla\Language\Language;
use Joomla\Registry\Registry;

use App\Controller\DefaultController;
use App\Router\Exception\RoutingException;
use App\Router\AppRouter;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 *
 * @since  1.0
 */
final class App extends AbstractWebApplication
{
	/**
	 * The Dispatcher object.
	 *
	 * @var    Dispatcher
	 * @since  1.0
	 */
	protected $dispatcher;

	/**
	 * The name of the application.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $name = null;

	/**
	 * The default theme.
	 *
	 * @var  string
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
	 * The User object.
	 *
	 * @var    User
	 * @since  1.0
	 */
	private $user;

	/**
	 * The database driver object.
	 *
	 * @var    DatabaseDriver
	 * @since  1.0
	 */
	private $database;

	/**
	 * The Language object
	 *
	 * @var    Language
	 * @since  1.0
	 */
	private $language;

	/**
	 * Class constructor.
	 *
	 * @since   1.0
	 */
	public function __construct()
	{
		// Run the parent constructor
		parent::__construct();

		// Load the configuration object.
		$this->loadConfiguration();

		// Register the event dispatcher
		$this->loadDispatcher();

		// Register the application to Factory
		// @todo Decouple from Factory
		Factory::$application = $this;
		Factory::$config = $this->config;

		$this->theme = $this->config->get('theme.default');

		define('BASE_URL', $this->get('uri.base.full'));
		define('DEFAULT_THEME', BASE_URL . '/themes/' . $this->theme);

		// Load the library language file
		$this->getLanguage()->load('lib_joomla', JPATH_BASE);
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
			$router = new Router\AppRouter($this->input, $this);
			$maps = json_decode(file_get_contents(JPATH_CONFIGURATION . '/routes.json'));

			if (!$maps)
			{
				throw new \RuntimeException('Invalid router file.', 500);
			}

			$router->addMaps($maps, true);
			$router->setControllerPrefix('\\App');
			$router->setDefaultController('\\App\\Controller\\DefaultController');
		
			// Fetch the controller
			$controller = $router->getController($this->get('uri.route'));	
			$content = $controller->execute();

			$this->setBody($content);
		}
		catch (\Exception $exception)
		{
			header('HTTP/1.1 500 Internal Server Error', true, 500);

			$this->setBody($exception);
		}
	}

	/**
	 * Method to send the application response to the client.  All headers will be sent prior to the main
	 * application output data.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function respond()
	{
		// Send the content-type header.
		$this->setHeader('Content-Type', $this->mimeType . '; charset=' . $this->charSet);

		// If the response is set to uncachable, we need to set some appropriate headers so browsers don't cache the response.
		if (!$this->response->cachable)
		{
			// Expires in the past.
			$this->setHeader('Expires', 'Mon, 1 Jan 2001 00:00:00 GMT', true);

			// Always modified.
			$this->setHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT', true);
			$this->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0', false);

			// HTTP 1.0
			$this->setHeader('Pragma', 'no-cache');
		}
		else
		{
			// Expires.
			$this->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + 900) . ' GMT');

			// Last modified.
			if ($this->modifiedDate instanceof \DateTime)
			{
				$this->modifiedDate->setTimezone(new \DateTimeZone('UTC'));
				$this->setHeader('Last-Modified', $this->modifiedDate->format('D, d M Y H:i:s') . ' GMT');
			}
		}

		$this->sendHeaders();

		$body = $this->getBody();
		
		if(file_exists(JPATH_THEMES . '/' . $this->theme . '/theme.php')) {
				include(JPATH_THEMES . '/' . $this->theme . '/theme.php');
		} else {
			echo $body;
		}
	}

	/**
	 * Initialize the configuration object.
	 *
	 * @return  $this  Method allows chaining
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	private function loadConfiguration()
	{
		// Set the configuration file path for the application.
		$file = JPATH_CONFIGURATION . '/config.json';

		// Verify the configuration exists and is readable.
		if (!is_readable($file))
		{
			throw new \RuntimeException('Configuration file does not exist or is unreadable.');
		}

		// Load the configuration file into an object.
		$config = json_decode(file_get_contents($file));

		if ($config === null)
		{
			throw new \RuntimeException(sprintf('Unable to parse the configuration file %s.', $file));
		}

		$this->config->loadObject($config);

		return $this;
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
	 * Provides a secure hash based on a seed
	 *
	 * @param   string  $seed  Seed string.
	 *
	 * @return  string  A secure hash
	 *
	 * @since   1.0
	 */
	public static function getHash($seed)
	{
		return md5(Factory::getConfig()->get('acl.secret') . $seed);
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

			// @todo Decouple from Factory
			Factory::$session = $this->newSession;
		}

		return $this->newSession;
	}

	/**
	 * Get a user object.
	 *
	 * @param   integer  $id  The user id or the current user.
	 *
	 * @return  User
	 *
	 * @since   1.0
	 */
	public function getUser($id = 0)
	{
		if ($id)
		{
			return new GitHubUser($id);
		}

		if (is_null($this->user))
		{
			$this->user = ($this->getSession()->get('user'))
				? : new GitHubUser;
		}

		return $this->user;
	}

	/**
	 * Get a database driver object.
	 *
	 * @return  DatabaseDriver
	 *
	 * @since   1.0
	 */
	public function getDatabase()
	{
		if (is_null($this->database))
		{
			$this->database = DatabaseDriver::getInstance(
				array(
					'driver' => $this->get('database.driver'),
					'host' => $this->get('database.host'),
					'user' => $this->get('database.user'),
					'password' => $this->get('database.password'),
					'database' => $this->get('database.name'),
					'prefix' => $this->get('database.prefix')
				)
			);

			// @todo Decouple from Factory
			Factory::$database = $this->database;
		}

		return $this->database;
	}

	/**
	 * Get a language object.
	 *
	 * @return  Language
	 *
	 * @since   1.0
	 */
	public function getLanguage()
	{
		if (is_null($this->language))
		{
			$this->language = Language::getInstance(
				$this->get('language')
			);
		}

		return $this->language;
	}

	/**
	 * Login or logout a user.
	 *
	 * @param   User  $user  The user object.
	 *
	 * @return  $this  Method allows chaining
	 *
	 * @since   1.0
	 */
	public function setUser(User $user = null)
	{
		if (is_null($user))
		{
			// Logout
			$this->user = new GitHubUser;

			$this->getSession()->set('user', $this->user);

			// @todo cleanup more ?
		}
		else
		{
			// Login
			$user->isAdmin = in_array($user->username, $this->get('acl.admin_users'));

			$this->user = $user;

			$this->getSession()->set('user', $user);
		}

		return $this;
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

	/**
	 * Method to get the application name.
	 *
	 * The dispatcher name is by default parsed using the class name, or it can be set
	 * by passing a $config['name'] in the class constructor.
	 *
	 * @return  string  The name of the dispatcher.
	 *
	 * @since   1.0
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Gets a user state.
	 *
	 * @param   string  $key      The path of the state.
	 * @param   mixed   $default  Optional default value, returned if the internal value is null.
	 *
	 * @return  mixed  The user state or null.
	 *
	 * @since   1.0
	 */
	public function getUserState($key, $default = null)
	{
		/* @type Registry $registry */
		$registry = $this->getSession()->get('registry');

		if (!is_null($registry))
		{
			return $registry->get($key, $default);
		}

		return $default;
	}

	/**
	 * Gets the value of a user state variable.
	 *
	 * @param   string  $key      The key of the user state variable.
	 * @param   string  $request  The name of the variable passed in a request.
	 * @param   string  $default  The default value for the variable if not found. Optional.
	 * @param   string  $type     Filter for the variable, for valid values see {@link JFilterInput::clean()}. Optional.
	 *
	 * @return  mixed The request user state.
	 *
	 * @since   1.0
	 */
	public function getUserStateFromRequest($key, $request, $default = null, $type = 'none')
	{
		$cur_state = $this->getUserState($key, $default);
		$new_state = $this->input->get($request, null, $type);

		// Save the new value only if it was set in this request.
		if ($new_state !== null)
		{
			$this->setUserState($key, $new_state);
		}
		else
		{
			$new_state = $cur_state;
		}

		return $new_state;
	}

	/**
	 * Sets the value of a user state variable.
	 *
	 * @param   string  $key    The path of the state.
	 * @param   string  $value  The value of the variable.
	 *
	 * @return  mixed  The previous state, if one existed.
	 *
	 * @since   1.0
	 */
	public function setUserState($key, $value)
	{
		/* @type Registry $registry */
		$registry = $this->getSession()->get('registry');

		if (!is_null($registry))
		{
			return $registry->set($key, $value);
		}

		return null;
	}

	/**
	 * Allows the application to load a custom or default dispatcher.
	 *
	 * The logic and options for creating this object are adequately generic for default cases
	 * but for many applications it will make sense to override this method and create event
	 * dispatchers, if required, based on more specific needs.
	 *
	 * @param   Dispatcher  $dispatcher  An optional dispatcher object. If omitted, the factory dispatcher is created.
	 *
	 * @return  $this  Method allows chaining
	 *
	 * @since   1.0
	 */
	public function loadDispatcher(Dispatcher $dispatcher = null)
	{
		$this->dispatcher = ($dispatcher === null) ? new Dispatcher : $dispatcher;

		return $this;
	}

	/**
	 * Gets the default theme from the configuration
	 *
	 * @return  string
	 * @since  1.0
	 */
	public function getTheme()
	{
		return $this->theme;
	}

}
