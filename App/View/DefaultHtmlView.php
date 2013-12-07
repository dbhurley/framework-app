<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\View;

use App\App;
use App\View\Renderer\TwigExtension;

use Joomla\Model\ModelInterface;
use Joomla\View\AbstractView;
use Joomla\View\Renderer\RendererInterface;

/**
 * Default view class for the application
 *
 * @since  1.0
 */
class DefaultHtmlView extends AbstractView
{
	/**
	 * Application object
	 *
	 * @var    App
	 * @since  1.0
	 */
	protected $app;

	/**
	 * The view layout.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $layout = null;

	/**
	 * The view template engine.
	 *
	 * @var    RendererInterface
	 * @since  1.0
	 */
	protected $renderer = null;

	/**
	 * Method to instantiate the view.
	 *
	 * @param   App             $app             The application object.
	 * @param   ModelInterface  $model           The model object.
	 * @param   string|array    $templatesPaths  The templates paths.
	 *
	 * @throws  \RuntimeException
	 * @since   1.0
	 */
	public function __construct(App $app, ModelInterface $model, $templatesPaths = '')
	{
		parent::__construct($model);

		$this->app = $app;

		$renderer = $app->getContainer()->get('config')->get('renderer.type');

		$className = 'App\\View\\Renderer\\' . ucfirst($renderer);

		if (false == class_exists($className))
		{
			throw new \RuntimeException(sprintf('Invalid renderer: %s', $renderer));
		}

		$config = array();
		$config['templates_base_dir'] = JPATH_TEMPLATES;

		// Load the renderer.
		$this->renderer = new $className($config);

		// Register application's Twig extension.
		$this->renderer->addExtension(new TwigExtension($app));

		// Register additional paths.
		if (!empty($templatesPaths))
		{
			$this->renderer->setTemplatesPaths($templatesPaths, true);
		}

		// Register the theme path
		$this->renderer->set('themePath', DEFAULT_THEME . '/');

		// Retrieve and clear the message queue
		$this->renderer->set('flashBag', $app->getMessageQueue());
		$app->clearMessageQueue();
	}

	/**
	 * Magic toString method that is a proxy for the render method.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Method to escape output.
	 *
	 * @param   string  $output  The output to escape.
	 *
	 * @return  string  The escaped output.
	 *
	 * @see     ViewInterface::escape()
	 * @since   1.0
	 */
	public function escape($output)
	{
		// Escape the output.
		return htmlspecialchars($output, ENT_COMPAT, 'UTF-8');
	}

	/**
	 * Method to get the view layout.
	 *
	 * @return  string  The layout name.
	 *
	 * @since   1.0
	 */
	public function getLayout()
	{
		return $this->layout;
	}

	/**
	 * Method to get the renderer object.
	 *
	 * @return  RendererInterface  The renderer object.
	 *
	 * @since   1.0
	 */
	public function getRenderer()
	{
		return $this->renderer;
	}

	/**
	 * Method to render the view.
	 *
	 * @return  string  The rendered view.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function render()
	{
		return $this->renderer->render($this->layout);
	}

	/**
	 * Method to set the view layout.
	 *
	 * @param   string  $layout  The layout name.
	 *
	 * @return  $this  Method supports chaining
	 *
	 * @since   1.0
	 */
	public function setLayout($layout)
	{
		$this->layout = $layout;

		return $this;
	}
}
