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

/**
 * Handles mobile browsers.
 *
 * @global array $cf
 * @global array $pth
 * @global string $e
 * @return void
 */
function Handheld_main()
{
    global $pth, $cf, $e, $plugin_cf, $plugin_tx;

    $pcf = $plugin_cf['handheld'];
    $ptx = $plugin_tx['handheld'];
    if (Handheld_detected()) {
        switch ($pcf['mode']) {
        case '1':
        case '2':
            if (headers_sent($file, $line)) {
                $msg = str_replace(
                    array('{file}', '{line}'), array($file, $line),
                    $ptx['error_redirect_details']
                );
                $e .= '<li>' . $ptx['error_redirect_caption'] . tag('br') . $msg
                    . '</li>';
            } else {
                $url = $pcf['mode'] == 1 ? $pcf['destination'] : CMSIMPLE_URL
                    . $pcf['subsite'] . '/';
                header('Location: ' . $url, true);
                exit();
            }
            break;
        case '3':
            $cf['site']['template'] = $pcf['template'];
            $pth['folder']['template'] = $pth['folder']['templates']
                . $cf['site']['template'] . '/';
            $pth['file']['template'] = $pth['folder']['template'] . 'template.htm';
            $pth['file']['stylesheet'] = $pth['folder']['template']
                . 'stylesheet.css';
            $pth['folder']['menubuttons'] = $pth['folder']['template'] . 'menu/';
            $pth['folder']['templateimages'] = $pth['folder']['template']
                . 'images/';
            break;
        }
    }
}

/**
 * Returns the requirements information view.
 *
 * @return string (X)HTML.
 */
function Handheld_systemCheck() // RELEASE-TODO
{
    global $pth, $tx, $plugin_tx;

    define('HANDHELD_PHP_VERSION', '4.0.7');
    $ptx = $plugin_tx['handheld'];
    $imgdir = $pth['folder']['plugins'] . 'handheld/images/';
    $ok = tag('img src="' . $imgdir . 'ok.png" alt="ok"');
    $warn = tag('img src="' . $imgdir . 'warn.png" alt="warning"');
    $fail = tag('img src="' . $imgdir . 'fail.png" alt="failure"');
    $o = '<h4>' . $ptx['syscheck_title'] . '</h4>'
        . (version_compare(PHP_VERSION, HANDHELD_PHP_VERSION) >= 0 ? $ok : $fail)
        . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_phpversion'], HANDHELD_PHP_VERSION)
        . tag('br') . "\n";
    foreach (array('pcre') as $ext) {
        $o .= (extension_loaded($ext) ? $ok : $fail)
            . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_extension'], $ext)
            . tag('br') . "\n";
    }
    $o .= (!get_magic_quotes_runtime() ? $ok : $fail)
        . '&nbsp;&nbsp;' . $ptx['syscheck_magic_quotes'] . tag('br') . tag('br')
        . "\n";
    $o .= (strtoupper($tx['meta']['codepage']) == 'UTF-8' ? $ok : $fail)
        . '&nbsp;&nbsp;' . $ptx['syscheck_encoding'] . tag('br') . "\n";
    $folders = array();
    foreach (array('config/', 'languages/') as $folder) {
        $folders[] = $pth['folder']['plugins'] . 'handheld/' . $folder;
    }
    foreach ($folders as $folder) {
        $o .= (is_writable($folder) ? $ok : $warn)
            . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_writable'], $folder)
            . tag('br') . "\n";
    }
    return $o;
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
    include_once $pth['folder']['plugins'].'handheld/handheld.inc.php';
    Handheld_main();
}

?>
