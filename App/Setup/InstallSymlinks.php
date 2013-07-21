<?php
/**
 * @copyright  Copyright (C) 2012 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace App\Setup;

use Composer\Script\Event;

/**
 *
 * @since  1.0
 */
class InstallSymlinks
{
    public static function postPackageInstall(Event $event)
	{
		$bsImagePath = '../../vendor/twitter/bootstrap/docs/assets/img';
		$imgAssetsPath = 'www/assets/img';
		symlink($bsImagePath,$imgAssetsPath);

		$bsJSPath = '../../vendor/twitter/bootstrap/docs/assets/js';
		$jsAssetsPath = 'www/assets/js';
		symlink($bsJSPath,$jsAssetsPath);

		$bsCSSPath = '../../vendor/twitter/bootstrap/docs/assets/css';
		$cssAssetsPath = 'www/assets/css';
		symlink($bsCSSPath,$cssAssetsPath);
	}
}