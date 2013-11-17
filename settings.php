<?php

// Site settings
date_default_timezone_set('Pacific/Auckland');
define('NAME_OF_SITE','My Website');
define('ADMIN_USERNAME', '');
define('ADMIN_PASSWORD','');
define('FOOTER_TEXT', 'Source code for this site available from <a class="c1" href="https://github.com/markjones112358/website-base">github.com/markjones112358/website-base</a>');

// Logo names - Logos live in /static
define('LOGO_LARGE','identicon_210.png');
define('LOGO_SMALL','identicon_105.png');

// Colour theme:
define('COLOR_0', '#000000');
define('COLOR_1', '#3c3c3c');
define('COLOR_2', '#696969');
define('COLOR_3', '#c3c3c3');
define('COLOR_4', '#f0f0f0');
define('COLOR_5', '#ffffff');
define('COLOR_x', '#9bd5e1');

// Override public_html (define outside directory base)
// Defined in a separate file so git can ignore it
if (file_exists($_SERVER['DOCUMENT_ROOT'] . '../pathOverride.txt')) {
    define('BASE_PATH', trim(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '../pathOverride.txt')));
}
else define('BASE_PATH', $_SERVER['DOCUMENT_ROOT']);
