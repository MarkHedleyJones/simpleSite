<?php
/*
 * Takes all incoming page requests and loads in the appropriate page.
 * This page loads the master reference to /lib/base that each of the views
 * make reference of.
 */
include(dirname($_SERVER['DOCUMENT_ROOT']) . '/config.php');
include(dirname($_SERVER['DOCUMENT_ROOT']) . '/lib/PHP-HTMLifier/lib_html.php');
include(dirname($_SERVER['DOCUMENT_ROOT']) . '/lib/lib_website-base.php');
include(dirname($_SERVER['DOCUMENT_ROOT']) . '/lib/class_Experience.php');


$experienceList = new ExperienceList($_SERVER['REQUEST_URI']);
$depth = count(url_array());

if ($depth == 0) include("../lib/views/landing.php");
elseif ($depth == 1) include("../lib/views/sub_page.php");
elseif ($depth == 2) include("../lib/views/sub_sub_page.php");
