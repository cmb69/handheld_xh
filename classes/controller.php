<?php

/**
 * Controller of Handheld_XH
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

/**
 * The controller class.
 *
 * @category CMSimple_XH
 * @package  Handheld
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Handheld_XH
 */
class Handheld_Controller
{
    /**
     * Returns the path of the plugin icon.
     *
     * @return string
     *
     * @global array The paths of system files and folders.
     *
     * @access protected
     */
    function iconPath()
    {
        global $pth;

        return $pth['folder']['plugins'] . 'handheld/handheld.png';
    }

    /**
     * Renders a template view.
     *
     * @return string (X)HTML.
     *
     * @access protected
     */
    function render($template)
    {
        global $pth;

        $template = $pth['folder']['plugins'] . 'handheld/views/' . $template . '.htm';
        ob_start();
        include $template;
        $o = ob_get_clean();
        return $o;
    }

    /**
     * Handles the plugin administration.
     *
     * @return void
     *
     * @global string The value of the "admin" GET or POST parameter.
     * @global string The value of the "action" GET or POST parameter.
     * @global string The name of the plugin.
     * @global string The (X)HTML to be placed in the contents area.
     *
     * @access protected
     */
    function handleAdministration()
    {
        global $admin, $action, $plugin, $o;

        $o .= print_plugin_admin('off');
        switch ($admin) {
        case '':
            $o .= $this->render('about') . tag('hr') . Handheld_systemCheck();
            break;
        default:
            $o .= plugin_admin_common($action, $admin, $plugin);
        }
    }

    /**
     * Overrides the mobile detection.
     *
     * @return void
     *
     * @access protected
     */
    function overrideDetection()
    {
        setcookie('handheld_full', $_GET['handheld_full'], 0, CMSIMPLE_ROOT);
        $_COOKIE['handheld_full'] = $_GET['handheld_full'];
    }

    /**
     * Dispatches on Sitemapper related requests.
     *
     * @return void
     *
     * @global bool   Whether the user is logged in as admin.
     * @global string Whether the plugin administration is requested.
     *
     * @access public
     */
    function dispatch()
    {
        global $adm, $handheld;

        if ($adm && isset($handheld) && $handheld == 'true') {
            $this->handleAdministration();
        } elseif (isset($_GET['handheld_full'])) {
            $this->overrideDetection();
        }
    }
}

?>
