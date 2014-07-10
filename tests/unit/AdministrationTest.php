<?php

/**
 * Testing the general plugin administration.
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Handheld
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      http://3-magi.net/?CMSimple_XH/Handheld_XH
 */

require_once './vendor/autoload.php';
require_once '../../cmsimple/adminfuncs.php';
require_once './classes/controller.php';

/**
 * Testing the general plugin administration.
 *
 * @category Testing
 * @package  Handheld
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Handheld_XH
 */
class AdministrationTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests the stylesheet administration.
     *
     * @return void
     *
     * @global bool   Whether we're in admin mode.
     * @global string Whether the plugin administration is requested.
     * @global string The value of the <var>admin</var> GP parameter.
     * @global string The value of the <var>action</var> GP parameter.
     */
    public function testStylesheet()
    {
        global $adm, $handheld, $admin, $action;

        $adm = true;
        $handheld = 'true';
        $admin = 'plugin_stylesheet';
        $action = 'plugin_text';
        $subject = new Handheld_Controller();
        $printPluginAdmin = new PHPUnit_Extensions_MockFunction(
            'print_plugin_admin', $subject
        );
        $printPluginAdmin->expects($this->once())->with('off');
        $pluginAdminCommon = new PHPUnit_Extensions_MockFunction(
            'plugin_admin_common', $subject
        );
        $pluginAdminCommon->expects($this->once())
            ->with($action, $admin, 'handheld');
        $subject->dispatch();
    }
}

?>
