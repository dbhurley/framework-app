<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Table;

use Joomla\Database\DatabaseDriver;
use Joomla\Filter\InputFilter;
use Joomla\Filter\OutputFilter;

use App\Table\DefaultDatabaseTable;

/**
 * Table interface class for the #__news table
 *
 * @property   integer  $news_id  PK
 * @property   string   $title    News title
 * @property   string   $alias    News title alias
 * @property   string   $body     News Body
 *
 * @since  1.0
 */
class NewsTable extends DefaultDatabaseTable
{
	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  $db  A database connector object
	 *
	 * @since   1.0
	 */
	public function __construct(DatabaseDriver $db)
	{
		parent::__construct('#__news', 'news_id', $db);
	}

	/**
	 * Method to perform sanity checks on the Table instance properties to ensure
	 * they are safe to store in the database.
	 *
	 * @throws \UnexpectedValueException
	 * @since   1.0
	 *
	 * @return  ProjectsTable
	 */
	public function check()
	{
		if (!$this->title)
		{
			throw new \UnexpectedValueException('A title is required');
		}

		if (!$this->alias)
		{
			$this->alias = $this->title;
		}

		$this->alias = OutputFilter::stringURLSafe($this->alias);

		return $this;
	}

	/**
	 * Method to store a row in the database from the DefaultDatabaseTable instance properties.
	 * If a primary key value is set the row with that primary key value will be
	 * updated with the instance property values.  If no primary key value is set
	 * a new row will be inserted into the database with the properties from the
	 * DefaultDatabaseTable instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  $this  Method allows chaining
	 *
	 * @since   1.0
	 */
	public function store($updateNulls = false)
	{
		$oldId = $this->{$this->getKeyName()};

		return parent::store($updateNulls);
	}
}
