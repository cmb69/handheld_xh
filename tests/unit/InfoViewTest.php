<?php

/**
 * Testing the info view.
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
require_once '../../cmsimple/adminfuncs.php';
require_once './classes/Presentation.php';

/**
 * Testing the info view.
 *
 * @category Testing
 * @package  Handheld
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Handheld_XH
 */
class InfoViewTest extends PHPUnit_Framework_TestCase
{
    /**
     * The subject under test.
     *
     * @var Handheld_Controller
     */
    private $_subject;

    /**
     * Sets up the test fixture.
     *
     * @return void
     *
     * @global bool   Whether we're in admin mode.
     * @global string Whether the plugin administration is requested.
     * @global string The (X)HTML of the contents area.
     * @global array  The paths of system files and folders.
     * @global array  The localization of the plugins.
     */
    public function setUp()
    {
        global $adm, $handheld, $o, $pth, $plugin_tx;

        $adm = true;
        $this->_defineConstant('HANDHELD_VERSION', '1.0');
        $handheld = 'true';
        $o = '';
        $pth = array(
            'folder' => array('plugins' => '../')
        );
        $plugin_tx = array(
            'handheld' => array(
                'alt_icon' => 'iPhone',
                'about' => '',
                'syscheck_title' => '',
                'syscheck_phpversion' => '',
                'syscheck_extension' => '',
                'syscheck_encoding' => '',
                'syscheck_magic_quotes' => '',
                'syscheck_writable' => ''
            )
        );
        $this->_subject = new Handheld_Controller();
        $rspmi = new PHPUnit_Extensions_MockFunction(
            'XH_registerStandardPluginMenuItems', $this->_subject
        );
        $printPluginAdmin = new PHPUnit_Extensions_MockFunction(
            'print_plugin_admin', $this->_subject
        );
        $this->_subject->dispatch();
    }

    /**
     * Tests that the heading is rendered.
     *
     * @return void
     *
     * @global string The (X)HTML of the contents area.
     */
    public function testRendersHeading()
    {
        global $o;

        $this->assertTag(
            array(
                'tag' => 'h1',
                'content' => 'Handheld'
            ),
            $o
        );
    }

    /**
     * Tests that the plugin icon is rendered.
     *
     * @return void
     *
     * @global string The (X)HTML of the contents area.
     */
    public function testRendersIcon()
    {
        global $o;

        $this->assertTag(
            array(
                'tag' => 'img',
                'attributes' => array(
                    'src' => '../handheld/handheld.png',
                    'class' => 'handheld_icon',
                    'alt' => 'iPhone'
                )
            ),
            $o
        );
    }

    /**
     * Tests that the version info is rendered.
     *
     * @return void
     *
     * @global string The (X)HTML of the contents area.
     */
    public function testRendersVersion()
    {
        global $o;

        $this->assertTag(
            array(
                'tag' => 'p',
                'content' => 'Version: ' . HANDHELD_VERSION
            ),
            $o
        );
    }

    /**
     * Tests that the copyright info is rendered.
     *
     * @return void
     *
     * @global string The (X)HTML of the contents area.
     */
    public function testRendersCopyright()
    {
        global $o;

        $this->assertTag(
            array(
                'tag' => 'p',
                'content' => "Copyright \xC2\xA9 2012-2014",
                'child' => array(
                    'tag' => 'a',
                    'attributes' => array(
                        'href' => 'http://3-magi.net/',
                        'target' => '_blank'
                    ),
                    'content' => 'Christoph M. Becker'
                )
            ),
            $o
        );
    }

    /**
     * Tests that the license info is rendered.
     *
     * @return void
     *
     * @global string The (X)HTML of the contents area.
     */
    public function testRendersLicense()
    {
        global $o;

        $this->assertTag(
            array(
                'tag' => 'p',
                'attributes' => array('class' => 'handheld_license'),
                'content' => 'This program is free software:'
            ),
            $o
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
