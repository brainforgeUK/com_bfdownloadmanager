<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_bfdownloadmanager
 *
 * @copyright   Copyright (C) 2018 Jonathan Brain. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Routing class of com_bfdownloadmanager
 *
 * @since  3.3
 */
class BfdownloadmanagerRouter extends JComponentRouterView
{
	protected $noIDs = false;

	/**
	 * Bfdownloadmanager Component router constructor
	 *
	 * @param JApplicationCms $app The application object
	 * @param JMenu $menu The menu object to work with
	 */
	public function __construct($app = null, $menu = null)
	{
		$params = JComponentHelper::getParams('com_bfdownloadmanager');
		$this->noIDs = (bool)$params->get('sef_ids');
		$categories = new JComponentRouterViewconfiguration('categories');
		$categories->setKey('id');
		$this->registerView($categories);
		$category = new JComponentRouterViewconfiguration('category');
		$category->setKey('id')->setParent($categories, 'catid')->setNestable()->addLayout('blog');
		$this->registerView($category);
		$download = new JComponentRouterViewconfiguration('download');
		$download->setKey('id')->setParent($category, 'catid');
		$this->registerView($download);
		$this->registerView(new JComponentRouterViewconfiguration('archive'));
		$this->registerView(new JComponentRouterViewconfiguration('featured'));
		$form = new JComponentRouterViewconfiguration('form');
		$form->setKey('a_id');
		$this->registerView($form);

		parent::__construct($app, $menu);

		$this->attachRule(new JComponentRouterRulesMenu($this));

		if ($params->get('sef_advanced', 0))
		{
			$this->attachRule(new JComponentRouterRulesStandard($this));
			$this->attachRule(new JComponentRouterRulesNomenu($this));
		}
		else
		{
			JLoader::register('BfdownloadmanagerRouterRulesLegacy', __DIR__ . '/helpers/legacyrouter.php');
			$this->attachRule(new BfdownloadmanagerRouterRulesLegacy($this));
		}
	}

	/**
	 * Method to get the segment(s) for a category
	 *
	 * @param string $id ID of the category to retrieve the segments for
	 * @param array $query The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getCategorySegment($id, $query)
	{
		$category = JCategories::getInstance($this->getName())->get($id);

		if ($category)
		{
			$path = array_reverse($category->getPath(), true);
			$path[0] = '1:root';

			if ($this->noIDs)
			{
				foreach ($path as &$segment)
				{
					list($id, $segment) = explode(':', $segment, 2);
				}
			}

			return $path;
		}

		return array();
	}

	/**
	 * Method to get the segment(s) for a category
	 *
	 * @param string $id ID of the category to retrieve the segments for
	 * @param array $query The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getCategoriesSegment($id, $query)
	{
		return $this->getCategorySegment($id, $query);
	}

	/**
	 * Method to get the segment(s) for an download
	 *
	 * @param string $id ID of the download to retrieve the segments for
	 * @param array $query The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getDownloadSegment($id, $query)
	{
		if (!strpos($id, ':'))
		{
			$db = JFactory::getDbo();
			$dbquery = $db->getQuery(true);
			$dbquery->select($dbquery->qn('alias'))
				->from($dbquery->qn('#__bfdownloadmanager'))
				->where('id = ' . $dbquery->q($id));
			$db->setQuery($dbquery);

			$id .= ':' . $db->loadResult();
		}

		if ($this->noIDs)
		{
			list($void, $segment) = explode(':', $id, 2);

			return array($void => $segment);
		}

		return array((int)$id => $id);
	}

	/**
	 * Method to get the segment(s) for a form
	 *
	 * @param string $id ID of the download form to retrieve the segments for
	 * @param array $query The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 *
	 * @since   3.7.3
	 */
	public function getFormSegment($id, $query)
	{
		return $this->getDownloadSegment($id, $query);
	}

	/**
	 * Method to get the id for a category
	 *
	 * @param string $segment Segment to retrieve the ID for
	 * @param array $query The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getCategoryId($segment, $query)
	{
		if (isset($query['id']))
		{
			$category = JCategories::getInstance($this->getName(), array('access' => false))->get($query['id']);

			if ($category)
			{
				foreach ($category->getChildren() as $child)
				{
					if ($this->noIDs)
					{
						if ($child->alias == $segment)
						{
							return $child->id;
						}
					}
					else
					{
						if ($child->id == (int)$segment)
						{
							return $child->id;
						}
					}
				}
			}
		}

		return false;
	}

	/**
	 * Method to get the segment(s) for a category
	 *
	 * @param string $segment Segment to retrieve the ID for
	 * @param array $query The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getCategoriesId($segment, $query)
	{
		return $this->getCategoryId($segment, $query);
	}

	/**
	 * Method to get the segment(s) for an download
	 *
	 * @param string $segment Segment of the download to retrieve the ID for
	 * @param array $query The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getDownloadId($segment, $query)
	{
		if ($this->noIDs)
		{
			$db = JFactory::getDbo();
			$dbquery = $db->getQuery(true);
			$dbquery->select($dbquery->qn('id'))
				->from($dbquery->qn('#__bfdownloadmanager'))
				->where('alias = ' . $dbquery->q($segment))
				->where('catid = ' . $dbquery->q($query['id']));
			$db->setQuery($dbquery);

			return (int)$db->loadResult();
		}

		return (int)$segment;
	}
}

/**
 * Bfdownloadmanager router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @param array  &$query An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function bfdownloadmanagerBuildRoute(&$query)
{
	$app = JFactory::getApplication();
	$router = new BfdownloadmanagerRouter($app, $app->getMenu());

	return $router->build($query);
}

/**
 * Parse the segments of a URL.
 *
 * This function is a proxy for the new router interface
 * for old SEF extensions.
 *
 * @param array $segments The segments of the URL to parse.
 *
 * @return  array  The URL attributes to be used by the application.
 *
 * @since   3.3
 * @deprecated  4.0  Use Class based routers instead
 */
function bfdownloadmanagerParseRoute($segments)
{
	$app = JFactory::getApplication();
	$router = new BfdownloadmanagerRouter($app, $app->getMenu());

	return $router->parse($segments);
}
