<?php
/**
 *
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Model;

use Joomla\Factory;
use Joomla\Model\AbstractDatabaseModel;
use Joomla\Database\DatabaseDriver;

use App\Table\DefaultDatabaseTable;

/**
 * Default model for the tracker application.
 *
 * @since  1.0
 */
class DefaultModel extends AbstractDatabaseModel
{
	/**
	 * Instantiate the model.
	 *
	 * @param   DatabaseDriver  $database  The database adapter.
	 *
	 * @since   1.0
	 */
	public function __construct(DatabaseDriver $database = null)
	{
		$database = (is_null($database)) ? Factory::$application->getDatabase() : $database;

		parent::__construct($database);
	}
}
