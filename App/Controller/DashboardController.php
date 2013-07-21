<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Controller;

use Joomla\Application\AbstractApplication;
use Joomla\Controller\AbstractController;
use Joomla\Input\Input;
use Joomla\Log\Log;
use App\App;
use App\View\DefaultHtmlView;
use App\Model\DashboardModel;

/**
 * Dashboard Controller class for the  Application
 *
 * @since  1.0
 */
class DashboardController extends DefaultController
{
	/**
	 * Constructor.
	 *
	 * @param   Input                $input  The input object.
	 * @param   AbstractApplication  $app    The application object.
	 *
	 * @since   1.0
	 */
	public function __construct(Input $input = null, AbstractApplication $app = null)
	{
		parent::__construct($input, $app);
		$this->defaultView = 'dashboard';
	}

	function updateDatabase()
	{
		try 
		{
			$dashboardModel = new DashboardModel;
			$dashboardModel->updateDatabase();
			
		} catch (\Exception $e)
		{
				throw new \RuntimeException(sprintf('Error: '.$e));
		}
	}
}