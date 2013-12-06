<?php
/**
 *
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Model;

use Joomla\Factory;
use Joomla\Input\Input;
use Joomla\Model\AbstractDatabaseModel;
use Joomla\Database\DatabaseDriver;

/**
 * Default model for the tracker application.
 *
 * @since  1.0
 */
class DefaultModel extends AbstractDatabaseModel
{
	/**
	 * Input object
	 *
	 * @var    Input
	 * @since  1.0
	 */
	protected $input;

	/**
	 * Instantiate the model.
	 *
	 * @param   Input           $input  Input object.
	 * @param   DatabaseDriver  $db     The database adapter.
	 * @param   Registry        $state  The model state.
	 *
	 * @since   1.0
	 */
	public function __construct(Input $input, DatabaseDriver $db, Registry $state = null)
	{
		parent::__construct($db);

		$this->input = $input;
	}
}
