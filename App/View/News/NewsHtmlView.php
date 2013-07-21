<?php
/**
 *
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\View\News;

use Joomla\Factory;
use Joomla\Language\Text;
use Joomla\Model\ModelInterface;

use App\App;
use App\View\DefaultHtmlView;

/**
 * News HTML view class for the application
 *
 * @since  1.0
 */
class NewsHtmlView extends DefaultHtmlView
{
	function render()
	{
		switch($this->getLayout()) {
			case 'news.article':
				// Get the input
				$item = $this->model->getItem();
				$this->renderer->set('item',$item);
			break;
			
			case 'news.add':

			break;
			
			default:
			break;
		}

		return parent::render();
	}
}
		