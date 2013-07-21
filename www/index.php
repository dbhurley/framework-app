<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// Define required paths
define('JPATH_BASE',          dirname(__DIR__));
define('JPATH_CONFIGURATION', JPATH_BASE . '/App/Config');
define('JPATH_SETUP',         JPATH_BASE . '/App/Setup');
define('JPATH_ROOT',          JPATH_BASE);
define('JPATH_SITE',          JPATH_BASE);
define('JPATH_THEMES',        JPATH_BASE . '/www/themes');
define('JPATH_TEMPLATES',	  JPATH_BASE . '/App/Templates');

// Load the Composer autoloader
require JPATH_BASE . '/vendor/autoload.php';

// Load the Joomla Framework
require JPATH_BASE . '/vendor/joomla/framework/src/import.php';

// Instantiate the application.
$application = new App\App;

// Execute the application.
$application->execute();
