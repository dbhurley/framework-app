<?php
/**
 *
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\View\Dashboard;

use Joomla\Factory;
use Joomla\Language\Text;
use Joomla\Model\ModelInterface;

use App\App;
use App\View\DefaultHtmlView;

/**
 *
 * @since  1.0
 */
class DashboardHtmlView extends DefaultHtmlView
{
	function render()
	{
		$this->renderer->set('logo', DEFAULT_THEME.'/images/logo.png');
		return parent::render();
	}
}