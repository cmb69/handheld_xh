<?php

/**
 * Testing the controller.
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
require_once '../../cmsimple/functions.php';
require_once './classes/Presentation.php';

/**
 * Testing the controller.
 *
 * @category Testing
 * @package  Handheld
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Handheld_XH
 */
class ControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * The test subject.
     *
     * @var Handheld_Controller
     */
    private $_subject;

    /**
     * Sets up the test fixture.
     *
     * @return void
     */
    public function setUp()
    {
        include_once './handheld.inc.php';
        $this->_subject = new Handheld_Controller();
        $rspmi = new PHPUnit_Extensions_MockFunction(
            'XH_registerStandardPluginMenuItems', $this->_subject
        );
        $handheldDetected = new PHPUnit_Extensions_MockFunction(
            'Handheld_detected', $this->_subject
        );
        $handheldDetected->expects($this->any())->will($this->returnValue(true));
    }

    /**
     * Tests that the cookie is set when the full site is forced.
     *
     * @return void
     */
    public function testSetsCookieWhenFullSiteIsForced()
    {
        $this->_defineConstant('CMSIMPLE_ROOT', 'xh');
        $_GET['handheld_full'] = '1';
        $setcookie = new PHPUnit_Extensions_MockFunction(
            'setcookie', $this->_subject
        );
        $setcookie->expects($this->once())->with(
            'handheld_full', $_GET['handheld_full'], 0, CMSIMPLE_ROOT
        );
        $this->_subject->dispatch();
        $this->assertEquals(1, $_COOKIE['handheld_full']);
    }

    /**
     * Tests that we redirect to an external site in mode 1.
     *
     * @return void
     *
     * @global array The paths of system files and folders.
     * @global array The configuration of the plugins.
     */
    public function testRedirectsToExternalSiteInMode1()
    {
        global $pth, $plugin_cf;

        $pth['folder']['plugins'] = '../';
        $plugin_cf['handheld'] = array(
            'mode' => '1',
            'destination' => 'http://3-magi.net/'
        );
        $headersSent = new PHPUnit_Extensions_MockFunction(
            'headers_sent', $this->_subject
        );
        $headersSent->expects($this->any())->will($this->returnValue(false));
        $header = new PHPUnit_Extensions_MockFunction('header', $this->_subject);
        $header->expects($this->once())->with('Location: http://3-magi.net/');
        $exit = new PHPUnit_Extensions_MockFunction('XH_exit', $this->_subject);
        $exit->expects($this->once());
        $this->_subject->dispatch();
    }

    /**
     * Tests that we switch the template in mode 3.
     *
     * @return void
     *
     * @global array The paths of system files and folders.
     * @global array The configuration of the core.
     * @global array The configuration of the plugins.
     */
    public function testSwitchesTemplateInMode3()
    {
        global $pth, $cf, $plugin_cf;

        $pth['folder']['plugins'] = '../';
        $plugin_cf['handheld'] = array(
            'mode' => '3',
            'destination' => 'mobile'
        );
        $afterPluginLoading = new PHPUnit_Extensions_MockFunction(
            'XH_afterPluginLoading', $this->_subject
        );
        $afterPluginLoading->expects($this->once())
            ->with(array($this->_subject, 'switchTemplate'));
        $this->_subject->dispatch();
    }

    /**
     * Tests switching the template.
     *
     * @return void
     *
     * @global array The paths of system files and folders.
     * @global array The configuration of the core.
     * @global array The configuration of the plugins.
     */
    public function testSwitchTemplate()
    {
        global $pth, $cf, $plugin_cf;

        $plugin_cf['handheld']['template'] = 'mobile';
        $pth['folder']['templates'] = './templates/';
        $this->_subject->switchTemplate();
        $this->assertEquals('./templates/mobile/', $pth['folder']['template']);
        $this->assertEquals(
            './templates/mobile/template.htm', $pth['file']['template']
        );
        $this->assertEquals(
            './templates/mobile/stylesheet.css', $pth['file']['stylesheet']
        );
        $this->assertEquals(
            './templates/mobile/menu/', $pth['folder']['menubuttons']
        );
        $this->assertEquals(
            './templates/mobile/images/', $pth['folder']['templateimages']
        );
    }

    /**
     * (Re)defines a constant.
     *
     * @param string $name  A name.
     * @param string $value A value.
     *
     * @return void
     */
    private function _defineConstant($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        } else {
            runkit_constant_redefine($name, $value);
        }
    }
}

?>
