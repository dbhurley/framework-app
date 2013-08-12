<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Setup;

use Composer\Script\Event;

/**
 * Post-install class triggered during 'compser install' to set up system dependencies
 *
 * @since  1.0
 */
class InstallScript
{
    public static function postInstall(Event $event)
	{
		// Check if a config.json file exists, copy the config.dist.json if not
		if (!file_exists('App/Config/config.json'))
		{
			copy('App/Config/config.dist.json', 'App/Config/config.json');
		}

		// Make sure the assets directory exists
		if (!is_dir('www/assets'))
		{
			mkdir('www/assets', 0755);
		}

		// Symlink img
		$bsImagePath = '../../vendor/twbs/bootstrap/docs/assets/img';
		$imgAssetsPath = 'www/assets/img';

		if (!file_exists($imgAssetsPath))
		{
			symlink($bsImagePath, $imgAssetsPath);
		}

		// Symlink js
		$bsJSPath = '../../vendor/twbs/bootstrap/docs/assets/js';
		$jsAssetsPath = 'www/assets/js';

		if (!file_exists($jsAssetsPath))
		{
			symlink($bsJSPath, $jsAssetsPath);
		}

		// Symlink css
		$bsCSSPath = '../../vendor/twbs/bootstrap/docs/assets/css';
		$cssAssetsPath = 'www/assets/css';

		if (!file_exists($cssAssetsPath))
		{
			symlink($bsCSSPath, $cssAssetsPath);
		}
	}
}
