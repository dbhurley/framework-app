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
use App\Table\NewsTable;
use App\Model\DefaultModel;

/**
 * Model to get data for the news articles
 *
 * @since  1.0
 */
class NewsModel extends DefaultModel
{
	/**
	 * Retrieve a single news item
	 *
	 * @return  object  News item
	 *
	 * @since   1.0
	 * @throws  \UnexpectedValueException
	 */
	public function getItem()
	{
		$id    = $this->input->getUint('id');
		$alias = $this->input->get('id');

		if (!$id && !$alias)
		{
			throw new \UnexpectedValueException('No news identifier provided.');
		}

		$query = $this->db->getQuery(true)
			->select('a.*')
			->from($this->db->quoteName('#__news','a'));

		if ($id)
		{
			$query->where($this->db->quoteName('a.news_id') . ' = ' . (int) $id);
		}
		elseif ($alias)
		{
			$query->where($this->db->quoteName('a.alias') . ' = ' . $this->db->quote($alias));
		}

		return $this->db->setQuery($query)->loadObject();
	}

	/**
	 * Retrieve all news items
	 *
	 * @return  object  Container with news items
	 *
	 * @since   1.0
	 */
	public function getItems()
	{
		$query = $this->db->getQuery(true)
			->select('a.*')
			->from($this->db->quoteName('#__news','a'));

		return $this->db->setQuery($query)->loadObjectList();
	}
}
