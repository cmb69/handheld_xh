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
     * Returns the localization of a string.
     *
     * @param string $key A internationalization key.
     *
     * @return string
     *
     * @global array The localization of the plugins.
     *
     * @access protected
     */
    function l10n($key)
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
     * @access protected
     */
    function iconPath()
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
     * @access protected
     */
    function stateIconPath($state)
    {
        global $pth;

        return $pth['folder']['plugins'] . 'handheld/images/' . $state . '.png';
    }

    /**
     * Renders a template view.
     *
     * @return string (X)HTML.
     *
     * @global array The paths of system files and folders.
     * @global array The configuration of the core.
     *
     * @access protected
     */
    function render($template)
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
     * Returns an error notice that the redirection was not possible.
     *
     * @param string $file A file path.
     * @param int    $line A line number.
     *
     * @return string
     *
     * @access protected
     */
    function redirectError($file, $line)
    {
        $message = str_replace(
            array('{file}', '{line}'), array($file, $line),
            $this->l10n('error_redirect_details')
        );
        $o .= '<li>' . $this->l10n('error_redirect_caption') . tag('br')
            . $message . '</li>' . PHP_EOL;
        return $o;
    }

    /**
     * Redirect according to configuration.
     *
     * @return void
     *
     * @global array The configuration of the plugins.
     *
     * @access protected
     */
    function redirect()
    {
        global $plugin_cf;

        $pcf = $plugin_cf['handheld'];
        $url = $pcf['mode'] == 1
            ? $pcf['destination']
            : HANDHELD_URL . $pcf['subsite'] . '/';
        header('Location: ' . $url, true);
        exit();
    }

    /**
     * Switches the template.
     *
     * @global array The configuration of the core.
     * @global array The paths of system files and folders.
     *
     * @access protected
     */
    function switchTemplate($template)
    {
        global $cf, $pth;

        $cf['site']['template'] = $template;
        $pth['folder']['template'] = $pth['folder']['templates']
            . $cf['site']['template'] . '/';
        $pth['file']['template'] = $pth['folder']['template'] . 'template.htm';
        $pth['file']['stylesheet'] = $pth['folder']['template'] . 'stylesheet.css';
        $pth['folder']['menubuttons'] = $pth['folder']['template'] . 'menu/';
        $pth['folder']['templateimages'] = $pth['folder']['template'] . 'images/';
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
     * @access protected
     */
    function systemChecks()
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
            $o .= $this->render('info');
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
     * Handles mobile browsers.
     *
     * @return void
     *
     * @global string Error messages to display.
     * @global array  The configuration of the plugins.
     *
     * @access public
     */
    function handleMobiles()
    {
        global $e, $plugin_cf;

        $pcf = $plugin_cf['handheld'];
        switch ($pcf['mode']) {
        case '1':
        case '2':
            if (headers_sent($file, $line)) {
                $e = $this->redirectError($file, $line);
            } else {
                $this->redirect();
            }
            break;
        case '3':
            $this->switchTemplate($pcf['template']);
            break;
        }
    }

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
            $this->handleAdministration();
        } elseif (isset($_GET['handheld_full'])) {
            $this->overrideDetection();
        }
        if ($pcf['mode'] && empty($_COOKIE['handheld_full'])
            && ($pcf['mode'] != 2 || $sl != $pcf['subsite'])
        ) {
            include_once $pth['folder']['plugins'] . 'handheld/handheld.inc.php';
            if (Handheld_detected()) {
                $this->handleMobiles();
            }
        }
    }
}

?>
