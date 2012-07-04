<?php

/**
 * Back-End of Handheld_XH.
 *
 * Copyright (c) 2011 Brett Allen
 * Copyright (c) 2012 Christoph M. Becker (see license.txt)
 */


if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


/**
 * Returns the plugin version information view.
 *
 * @return string  The (X)HTML.
 */
function Handheld_version()
{
    global $pth;

    return '<h1><a href="http://3-magi.net/?CMSimple_XH/Handheld_XH">Handheld_XH</a></h1>'."\n"
	.tag('img src="'.$pth['folder']['plugins'].'handheld/handheld.png"'
	. ' style="float: left; margin: 0 0 10px 0"')
	.'<p>Version: '.HANDHELD_VERSION.'</p>'."\n"
	.'<p>Copyright &copy; 2011 <a href="http://www.videopoint.co.uk/">Brett Allen</a>' . tag('br')
	. 'Copyright &copy; 2012 <a href="http://3-magi.net/">Christoph M. Becker</a></p>'."\n"
	. '<p>Handheld_XH is powered by <a href="http://detectmobilebrowsers.com/">Detect Mobile Browsers</a>.</p>'
	.'<p style="text-align: justify; clear: both">This program is free software: you can redistribute it and/or modify'
	.' it under the terms of the GNU General Public License as published by'
	.' the Free Software Foundation, either version 3 of the License, or'
	.' (at your option) any later version.</p>'."\n"
	.'<p style="text-align: justify; clear: both">This program is distributed in the hope that it will be useful,'
	.' but WITHOUT ANY WARRANTY; without even the implied warranty of'
	.' MERCHAN&shy;TABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the'
	.' GNU General Public License for more details.</p>'."\n"
	.'<p style="text-align: justify; clear: both">You should have received a copy of the GNU General Public License'
	.' along with this program.  If not, see'
	.' <a href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.</p>'."\n";
}


/**
 * Returns the requirements information view.
 *
 * @return string  The (X)HTML.
 */
function Handheld_systemCheck() // RELEASE-TODO
{
    global $pth, $tx, $plugin_tx;

    define('HANDHELD_PHP_VERSION', '4.0.7');
    $ptx = $plugin_tx['handheld'];
    $imgdir = $pth['folder']['plugins'].'handheld/images/';
    $ok = tag('img src="'.$imgdir.'ok.png" alt="ok"');
    $warn = tag('img src="'.$imgdir.'warn.png" alt="warning"');
    $fail = tag('img src="'.$imgdir.'fail.png" alt="failure"');
    $o = '<h4>'.$ptx['syscheck_title'].'</h4>'
	    .(version_compare(PHP_VERSION, HANDHELD_PHP_VERSION) >= 0 ? $ok : $fail)
	    .'&nbsp;&nbsp;'.sprintf($ptx['syscheck_phpversion'], HANDHELD_PHP_VERSION)
	    .tag('br')."\n";
    foreach (array('pcre') as $ext) {
	$o .= (extension_loaded($ext) ? $ok : $fail)
		.'&nbsp;&nbsp;'.sprintf($ptx['syscheck_extension'], $ext).tag('br')."\n";
    }
    $o .= (!get_magic_quotes_runtime() ? $ok : $fail)
	    .'&nbsp;&nbsp;'.$ptx['syscheck_magic_quotes'].tag('br').tag('br')."\n";
    $o .= (strtoupper($tx['meta']['codepage']) == 'UTF-8' ? $ok : $fail)
	    .'&nbsp;&nbsp;'.$ptx['syscheck_encoding'].tag('br')."\n";
    $folders = array();
    foreach (array('config/', 'languages/') as $folder) {
	$folders[] = $pth['folder']['plugins'].'handheld/'.$folder;
    }
    foreach ($folders as $folder) {
	$o .= (is_writable($folder) ? $ok : $warn)
		.'&nbsp;&nbsp;'.sprintf($ptx['syscheck_writable'], $folder).tag('br')."\n";
    }
    return $o;
}


/*
 * Handle the plugin administration.
 */
if (isset($handheld) && $handheld == 'true') {
    $o .= print_plugin_admin('off');
    switch ($admin) {
    case '':
	$o .= Handheld_version() . tag('hr') . Handheld_systemCheck();
	break;
    default:
	$o .= plugin_admin_common($action, $admin, $plugin);
    }
}

?>
