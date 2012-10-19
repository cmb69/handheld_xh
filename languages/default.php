<?php

$plugin_tx['handheld']['cf_mode']="The mode for mobile browsers: \"\" (do nothing), \"1\" (redirect to another domain), \"2\" (redirect to a CMSimple subsite) or \"3\" (use another template)";
$plugin_tx['handheld']['cf_destination']="The fully qualified absolute URL where to redirect mobile browsers to, e.g. \"http://m.example.com/\". (requires mode=1)";
$plugin_tx['handheld']['cf_subsite']="The name of the CMSimple subsite where to redirect mobile browsers to, e.g. \"mobile\". (requires mode=2)";
$plugin_tx['handheld']['cf_template']="The name of the template, that should be used for mobile browsers. (requires mode=3)";

$plugin_tx['handheld']['error_redirect_caption']="<b>Redirection not possible!</b>";
$plugin_tx['handheld']['error_redirect_details']="The HTTP headers were already sent from {file} on line {line}.";

$plugin_tx['handheld']['syscheck_title']="System check";
$plugin_tx['handheld']['syscheck_phpversion']="PHP version &ge; %s";
$plugin_tx['handheld']['syscheck_extension']="Extension '%s' loaded";
$plugin_tx['handheld']['syscheck_encoding']="Encoding 'UTF-8' configured";
$plugin_tx['handheld']['syscheck_magic_quotes']="Magic quotes runtime off";
$plugin_tx['handheld']['syscheck_writable']="Folder '%s' writable";

?>
