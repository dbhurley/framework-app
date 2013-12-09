<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\View\Dashboard;

use Joomla\Factory;
use Joomla\Language\Text;

use App\View\DefaultHtmlView;

/**
 * Dashboard HTML view class for the application
 *
 * @since  1.0
 */
class DashboardHtmlView extends DefaultHtmlView
{
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
		if ($this->app->input->get('success', false))
		{
			$this->app->enqueueMessage("Sweet! You've setup your database successfully. Check out the <a href=\"news\">Sample Page</a>", 'success');
		}

		$this->renderer->set('success', $this->app->input->get('success', false));
		$this->renderer->set('logo', DEFAULT_THEME . '/images/logo.png');
		$this->renderer->set('config', $this->app->getContainer()->get('config'));

		return parent::render();
	}
}
