<?php

$plugin_tx['handheld']['cf_mode']="The mode for mobile browser: \"0\" (do nothing), \"1\" (redirect to another domain), \"2\" (redirect to a CMSimple sub-site) or \"3\" (use another template)";
$plugin_tx['handheld']['cf_destination']="The fully qualified absolute URL where to redirect mobile browsers to, e.g. \"http://m.example.com/\". (requires mode = 1)";
$plugin_tx['handheld']['cf_subsite']="The name of the CMSimple sub-site where to redirect mobile browsers to, e.g. \"mobile\". (requires mode = 1)";
$plugin_tx['handheld']['cf_template']="The name of the template, that should be used for mobile browsers. (requires mode = 3)";

$plugin_tx['handheld']['error_redirect']="<b>Redirection not possible!</b>" . tag('br') . "Headers were already sent from {file} in line {line}.";

$plugin_tx['handheld']['syscheck_title']="System check";
$plugin_tx['handheld']['syscheck_phpversion']="PHP version &ge; %s";
$plugin_tx['handheld']['syscheck_extension']="Extension '%s' loaded";
$plugin_tx['handheld']['syscheck_encoding']="Encoding 'UTF-8' configured";
$plugin_tx['handheld']['syscheck_magic_quotes']="Magic quotes runtime off";
$plugin_tx['handheld']['syscheck_writable']="Folder '%s' writable";

?>
