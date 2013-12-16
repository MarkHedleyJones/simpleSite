<?php

// Override public_html (define outside directory base)
// Defined in a separate file so git can ignore it
if (file_exists($_SERVER['DOCUMENT_ROOT'] . '../pathOverride.txt')) {
    define('BASE_PATH', trim(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '../pathOverride.txt')));
}
else define('BASE_PATH', $_SERVER['DOCUMENT_ROOT']);

include(BASE_PATH . 'siteSettings/settings.php');