<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Model;

use Joomla\Factory;
use Joomla\Filter\InputFilter;
use Joomla\Registry\Registry;
use Joomla\String\String;

use App\Model\DefaultModel;

/**
 * Model to get data for the issue list view
 *
 * @since  1.0
 */
class DashboardModel extends DefaultModel
{
	/**
	 * Update the database configuration
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function updateDatabase()
	{
		$input = Factory::$application->input;
		$oldConfig = Factory::getConfig();

		$file = JPATH_CONFIGURATION . '/config.json';

		// Verify the configuration exists and is readable.
		if (!is_readable($file))
		{
			throw new \RuntimeException('Configuration file does not exist or is unreadable.');
		}

		$config = json_decode(file_get_contents($file));

		$config->database->driver = $input->get('driver', $oldConfig->get('database.driver'));
		$config->database->user = $input->get('user', $oldConfig->get('database.user'));
		$config->database->password = $input->get('password', $oldConfig->get('database.password'));
		$config->database->name = $input->get('name', $oldConfig->get('database.name'));
		$config->database->host = $input->get('host', $oldConfig->get('database.host'));
		$config->database->prefix = $input->get('prefix', $oldConfig->get('database.prefix'));

		file_put_contents($file, json_encode($config));

		Factory::$application->loadConfiguration();

		if ($input->get('install_sample_data'))
		{
			$this->installSampleData();
		}

		return true;
	}

	/**
	 * Install sample data for the application
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 * @throws  \UnexpectedValueException
	 */
	public function installSampleData()
	{
		$sampleData = JPATH_SETUP . '/sampleData.sql';

		if (!is_readable($sampleData))
		{
			throw new \RuntimeException('Sample Data file does not exist or is unreadable');
		}

		$sql = file_get_contents($sampleData);

		if (false == $sql)
		{
			throw new \UnexpectedValueException('SQL file corrupted.');
		}

		foreach ($this->db->splitSql($sql) as $query)
		{
			$q = trim($this->db->replacePrefix($query));

			if ('' == trim($q))
			{
				continue;
			}

			$this->db->setQuery($q)->execute();
		}

		return true;
	}
}
