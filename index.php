<?php

/**
 * Front-end of Handheld_XH
 *
 * PHP versions 4 and 5
 *
 * @category  CMSimple_XH
 * @package   Handheld
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2011 Brett Allen <http://www.videopoint.co.uk/>
 * @copyright 2012-2013 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Handheld_XH
 */

/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

/**
 * The controller class.
 */
require_once $pth['folder']['plugin_classes'] . 'controller.php';

/**
 * The version string.
 */
define('HANDHELD_VERSION', '1beta5');

/**
 * Fully qualified absolute URL to CMSimple's root folder.
 */
if (!defined('CMSIMPLE_URL')) {
    define(
        'CMSIMPLE_URL',
        'http'
        . (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 's' : '')
        . '://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT']
        . preg_replace('/index.php$/', '', $_SERVER['PHP_SELF'])
    );
}

$_Handheld = new Handheld_Controller();
$_Handheld->dispatch();

/*
 * Handle mobile browsers.
 */
if ($plugin_cf['handheld']['mode'] && empty($_COOKIE['handheld_full'])
    && ($plugin_cf['handheld']['mode'] != 2
    || $sl != $plugin_cf['handheld']['subsite'])
) {
    include_once $pth['folder']['plugins'] . 'handheld/handheld.inc.php';
    if (Handheld_detected()) {
        $_Handheld->handleMobiles();
    }
}

?>
