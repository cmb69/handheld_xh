<?php

/**
 * The presentation layer.
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

/**
 * The controllers.
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
     * Dispatches on Handheld related requests.
     *
     * @return void
     *
     * @global bool   Whether the user is logged in as admin.
     * @global string Whether the plugin administration is requested.
     * @global string The current language.
     * @global array  The paths of system files and folders.
     * @global array  The configuration of the plugins.
     *
     * @access public
     */
    function dispatch()
    {
        global $adm, $handheld, $sl, $pth, $plugin_cf;

        $pcf = $plugin_cf['handheld'];
        if ($adm && isset($handheld) && $handheld == 'true') {
            $this->_handleAdministration();
        } elseif (isset($_GET['handheld_full'])) {
            $this->_overrideDetection();
        }
        if ($pcf['mode'] && empty($_COOKIE['handheld_full'])
            && ($pcf['mode'] != 2 || $sl != $pcf['subsite'])
        ) {
            include_once $pth['folder']['plugins'] . 'handheld/handheld.inc.php';
            if (Handheld_detected()) {
                $this->_handleMobiles();
            }
        }
    }

    /**
     * Switches the template.
     *
     * @return void
     *
     * @global array The configuration of the core.
     * @global array The configuration of the plugins.
     * @global array The paths of system files and folders.
     *
     * @access public
     */
    function switchTemplate()
    {
        global $cf, $plugin_cf, $pth;

        $cf['site']['template'] = $plugin_cf['handheld']['template'];
        $pth['folder']['template'] = $pth['folder']['templates']
            . $cf['site']['template'] . '/';
        $pth['file']['template'] = $pth['folder']['template'] . 'template.htm';
        $pth['file']['stylesheet'] = $pth['folder']['template'] . 'stylesheet.css';
        $pth['folder']['menubuttons'] = $pth['folder']['template'] . 'menu/';
        $pth['folder']['templateimages'] = $pth['folder']['template'] . 'images/';
    }

    /**
     * Handles the plugin administration.
     *
     * @return void
     *
     * @global string The value of the "admin" GET or POST parameter.
     * @global string The value of the "action" GET or POST parameter.
     * @global string The (X)HTML to be placed in the contents area.
     *
     * @access private
     */
    function _handleAdministration()
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('off');
        switch ($admin) {
        case '':
            $o .= $this->_render('info');
            break;
        default:
            $o .= plugin_admin_common($action, $admin, 'handheld');
        }
    }

    /**
     * Renders a template view.
     *
     * @param string $template A name of a view template.
     *
     * @return string (X)HTML.
     *
     * @global array The paths of system files and folders.
     * @global array The configuration of the core.
     *
     * @access private
     */
    function _render($template)
    {
        global $pth, $cf;

        $template = $pth['folder']['plugins'] . 'handheld/views/' . $template
            . '.php';
        $xhtml = $cf['xhtml']['endtags'];
        unset($pth, $cf);
        ob_start();
        include $template;
        $o = ob_get_clean();
        if (!$xhtml) {
            $o = str_replace('/>', '>', $o);
        }
        return $o;
    }

    /**
     * Overrides the mobile detection.
     *
     * @return void
     *
     * @access private
     */
    function _overrideDetection()
    {
        setcookie('handheld_full', $_GET['handheld_full'], 0, CMSIMPLE_ROOT);
        $_COOKIE['handheld_full'] = $_GET['handheld_full'];
    }

    /**
     * Handles mobile browsers.
     *
     * @return void
     *
     * @global string Error messages to display.
     * @global array  The configuration of the plugins.
     *
     * @access private
     */
    function _handleMobiles()
    {
        global $e, $plugin_cf;

        $pcf = $plugin_cf['handheld'];
        switch ($pcf['mode']) {
        case '1':
        case '2':
            if (headers_sent($file, $line)) {
                $e = $this->_redirectError($file, $line);
            } else {
                $this->_redirect();
            }
            break;
        case '3':
            if (function_exists('XH_afterPluginLoading')) {
                XH_afterPluginLoading(array($this, 'switchTemplate'));
            } else {
                $this->switchTemplate();
            }
            break;
        }
    }

    /**
     * Returns an error notice that the redirection was not possible.
     *
     * @param string $file A file path.
     * @param int    $line A line number.
     *
     * @return string
     *
     * @access private
     */
    function _redirectError($file, $line)
    {
        $message = str_replace(
            array('{file}', '{line}'), array($file, $line),
            $this->_l10n('error_redirect_details')
        );
        $o .= '<li>' . $this->_l10n('error_redirect_caption') . tag('br')
            . $message . '</li>' . PHP_EOL;
        return $o;
    }

    /**
     * Redirects according to configuration.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     *
     * @access private
     */
    function _redirect()
    {
        global $plugin_cf;

        $pcf = $plugin_cf['handheld'];
        if ($pcf['mode'] == 1) {
            $url = $pcf['destination'];
        } else {
            $url = preg_replace('/index.php$/', '', CMSIMPLE_URL)
                . $pcf['subsite'] . '/';
        }
        header('Location: ' . $url, true);
        XH_exit();
    }

    /**
     * Returns the localization of a string.
     *
     * @param string $key A internationalization key.
     *
     * @return string
     *
     * @global array The localization of the plugins.
     *
     * @access private
     */
    function _l10n($key)
    {
        global $plugin_tx;

        return $plugin_tx['handheld'][$key];
    }

    /**
     * Returns the path of the plugin icon.
     *
     * @return string
     *
     * @global array The paths of system files and folders.
     *
     * @access private
     */
    function _iconPath()
    {
        global $pth;

        return $pth['folder']['plugins'] . 'handheld/handheld.png';
    }

    /**
     * Returns the path of a system check state icon.
     *
     * @param string $state A state.
     *
     * @return string
     *
     * @global array The paths of system files and folders.
     *
     * @access private
     */
    function _stateIconPath($state)
    {
        global $pth;

        return $pth['folder']['plugins'] . 'handheld/images/' . $state . '.png';
    }

    /**
     * Returns the system checks.
     *
     * @return array
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the core.
     * @global array The localization of the plugins.
     *
     * @access private
     */
    function _systemChecks()
    {
        global $pth, $tx, $plugin_tx;

        $ptx = $plugin_tx['handheld'];
        $phpVersion = '4.3.10';
        $checks = array();
        $checks[sprintf($ptx['syscheck_phpversion'], $phpVersion)]
            = version_compare(PHP_VERSION, $phpVersion) >= 0 ? 'ok' : 'fail';
        foreach (array('pcre') as $ext) {
            $checks[sprintf($ptx['syscheck_extension'], $ext)]
                = extension_loaded($ext) ? 'ok' : 'fail';
        }
        $checks[$ptx['syscheck_magic_quotes']]
            = !get_magic_quotes_runtime() ? 'ok' : 'fail';
        $checks[$ptx['syscheck_encoding']]
            = strtoupper($tx['meta']['codepage']) == 'UTF-8' ? 'ok' : 'warn';
        $folders = array();
        foreach (array('config/', 'languages/') as $folder) {
            $folders[] = $pth['folder']['plugins'] . 'handheld/' . $folder;
        }
        foreach ($folders as $folder) {
            $checks[sprintf($ptx['syscheck_writable'], $folder)]
                = is_writable($folder) ? 'ok' : 'warn';
        }
        return $checks;
    }
}

?>
