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


// Determine name of cached page (regardles of if it exists yet)
$pageLastModified = last_modified(url2path(url_string()));
$page_path = clean_path($_SERVER['DOCUMENT_ROOT'] . url_string());
$page_filename = $page_path . $pageLastModified . '.html';

if (file_exists($page_filename)) print file_get_contents($page_filename);
else {
    $depth = count(url_array());
    $experienceList = new ExperienceList($_SERVER['REQUEST_URI']);

    // Write the page to the buffer
    ob_start();
    if ($depth == 0) include("../lib/views/landing.php");
    elseif ($depth == 1) include("../lib/views/sub_page.php");
    elseif ($depth == 2) include("../lib/views/sub_sub_page.php");

    // Retrieve the page and clear the output buffer
    unset($p);
    if (file_exists($page_path) == False) mkdir($page_path, 0765, True);
    file_put_contents($page_filename, ob_get_flush());
}

