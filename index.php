<?php

/**
 * Front-end of Handheld_XH
 *
 * Copyright (c) 2011  Brett Allen
 * Copyright (c) 2012  Christoph M. Becker (see file LICENSE)
 */


if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


define('HANDHELD_VERSION', '1beta4');


/**
 * Fully qualified absolute URL to CMSimple's root folder.
 */
define('HANDHELD_URL',
    'http://' . (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 's' : '')
    . $_SERVER['SERVER_NAME'] . preg_replace('/index.php$/', '', $_SERVER['PHP_SELF']));


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
		$msg = str_replace(array('{file}', '{line}'),
		array($file, $line), $ptx['error_redirect_details']);
		$e .= '<li>' . $ptx['error_redirect_caption'] . tag('br') . $msg . '</li>';
	    } else {
		$url = $pcf['mode'] == 1 ? $pcf['destination'] : HANDHELD_URL . $pcf['subsite'] . '/';
		header('Location: ' . $url, true);
		exit();
	    }
	    break;
	case '3':
	    $cf['site']['template'] = $pcf['template'];
	    $pth['folder']['template'] = $pth['folder']['templates'] . $cf['site']['template'] . '/';
	    $pth['file']['template'] = $pth['folder']['template'] . 'template.htm';
	    $pth['file']['stylesheet'] = $pth['folder']['template'] . 'stylesheet.css';
	    $pth['folder']['menubuttons'] = $pth['folder']['template'] . 'menu/';
	    $pth['folder']['templateimages'] = $pth['folder']['template'] . 'images/';
	    break;
	}
    }
}


/*
 * Set cookie to force full/mobile site.
 */
if (isset($_GET['handheld_full'])) {
    setcookie('handheld_full', $_GET['handheld_full'], 0, CMSIMPLE_ROOT);
    $_COOKIE['handheld_full'] = $_GET['handheld_full'];
}


/*
 * Handle mobile browsers.
 */
if ($plugin_cf['handheld']['mode'] && empty($_COOKIE['handheld_full'])
    && ($plugin_cf['handheld']['mode'] != 2 || $sl != $plugin_cf['handheld']['subsite']))
{
    include_once $pth['folder']['plugins'].'handheld/handheld.inc.php';
    Handheld_main();
}

?>
