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

	function getItem()
	{
		$input = Factory::$application->input;
		$id = $input->getUint('id');
		$alias = $input->get('id');

		if(!$id && !$alias) return false;

		$query = $this->db->getQuery(true);
		$query->select('a.*')
			  ->from($this->db->quoteName('#__news','a'));
		
		if($id) 
		{
			$query->where($this->db->quoteName('a.news_id') . ' = ' . (int) $id);
		} elseif($alias) 
		{
			$query->where($this->db->quoteName('a.alias') . ' = ' . $this->db->quote($alias));
		}
			
		$this->db->setQuery($query);
		$data = $this->db->loadObject();

		return $data;
	}

	function getItems()
	{
		$query = $this->db->getQuery(true);
		$query->select('a.*')
			  ->from($this->db->quoteName('#__news','a'));

		$this->db->setQuery($query);
		$data = $this->db->loadObjectList();

		return $data;
	}
}