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
 * @copyright 2012-2014 Christoph M. Becker <http://3-magi.net/>
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
 * The presentation layer.
 */
require_once $pth['folder']['plugin_classes'] . 'Presentation.php';

/**
 * The version string.
 */
define('HANDHELD_VERSION', '@HANDHELD_VERSION@');

if (!defined('CMSIMPLE_URL')) {
    /**
     * The fully qualified absolute URL of the installation.
     */
    define(
        'CMSIMPLE_URL',
        'http'
        . (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 's' : '')
        . '://' . $_SERVER['HTTP_HOST'] . $sn
    );
}

if (!function_exists('XH_exit')) {
    /**
     * Exists the script. Required for mocking.
     *
     * @return void
     */
    function XH_exit()
    {
        exit;
    }
}

/**
 * The plugin controller.
 *
 * @var Handheld_Controller
 */
$_Handheld = new Handheld_Controller();
$_Handheld->dispatch();

?>
