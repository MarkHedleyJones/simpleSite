<?php
/*
 * Takes all incoming page requests and loads in the appropriate page.
 * This page loads the master reference to /lib/base that each of the views
 * make reference of.
 */
include(dirname($_SERVER['DOCUMENT_ROOT']) . '/config.php');
include(dirname($_SERVER['DOCUMENT_ROOT']) . '/lib/PHP-HTMLifier/HTMLifier.php');
include(dirname($_SERVER['DOCUMENT_ROOT']) . '/lib/lib_website-base.php');
include(dirname($_SERVER['DOCUMENT_ROOT']) . '/lib/class_Experience.php');
include(dirname($_SERVER['DOCUMENT_ROOT']) . '/lib/parsedown/Parsedown.php');
include(dirname($_SERVER['DOCUMENT_ROOT']) . '/lib/geshi/geshi.php');

if (!file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/public_html/' . LOGO_LARGE)) {
    if(file_exists(PATH_WATCH . '/.siteSettings/' . LOGO_LARGE)) {
        if (!copy(PATH_WATCH . '/.siteSettings/' . LOGO_LARGE, dirname($_SERVER['DOCUMENT_ROOT']) . '/public_html/' . LOGO_LARGE)) {
            trigger_error('Could not copy site logo to public_html');
        }
    }
    else {
        if (!copy(dirname($_SERVER['DOCUMENT_ROOT']) . '/static/logo_large.png', dirname($_SERVER['DOCUMENT_ROOT']) . '/public_html/' . LOGO_LARGE)) {
            trigger_error('Could not copy site logo to public_html');
        }
    }
}

if (!file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/public_html/' . LOGO_SMALL)) {
    if(file_exists(PATH_WATCH . '/.siteSettings/' . LOGO_SMALL)) {
        if (!copy(PATH_WATCH . '/.siteSettings/' . LOGO_SMALL, dirname($_SERVER['DOCUMENT_ROOT']) . '/public_html/' . LOGO_SMALL)) {
            trigger_error('Could not copy site logo to public_html');
        }
    }
    else {
        if (!copy(dirname($_SERVER['DOCUMENT_ROOT']) . '/static/logo_small.png', dirname($_SERVER['DOCUMENT_ROOT']) . '/public_html/' . LOGO_SMALL)) {
            trigger_error('Could not copy site logo to public_html');
        }
    }
}

if ($_SERVER['REQUEST_URI'] == '/sitemap.xml') {
    include("../lib/views/sitemap.php");
    die();
}

// Determine name of cached page (regardles of if it exists yet)
$pageLastModified = last_modified(url2path(url_string()));
$page_path = clean_path($_SERVER['DOCUMENT_ROOT'] . url_string());
$page_filename = $page_path . $pageLastModified . '.html';

// Chech for a flush command
if (strpos($_SERVER['REQUEST_URI'], '?refresh') != False) {
    $here = str_replace('?refresh/', '', url_string());
    foreach (scandir('.') as $a => $b) {
        if ($b[0] != '.' && $b != 'index.php') {
            $path = $_SERVER['DOCUMENT_ROOT'] . $b;
            if (is_dir($path)) {
                $cmd = "rm " . $path . " -rf";
                exec($cmd);
            }
            else {
                $cmd = "rm " . $path;
                exec($cmd);
            }
        }
    }
    redirect($here);

}

// Check for a 404
if (file_exists(url2path($_SERVER['REQUEST_URI'])) == False) {
    include("../lib/views/404.php");
    die();
}

$cache = False;
if (defined(PAGE_CACHING) && PAGE_CACHING) $cache = True;

if ($cache && file_exists($page_filename)) print file_get_contents($page_filename);
else {
    $depth = count(url_array());

    $experienceList = new ExperienceList($_SERVER['REQUEST_URI']);
    // Write the page to the buffer

    if ($cache) ob_start();

    if ($depth == 0) include("../lib/views/landing.php");
    elseif ($depth == 1) include("../lib/views/sub_page.php");
    elseif ($depth == 2) include("../lib/views/sub_sub_page.php");

    if ($cache) {
        // Retrieve the page and clear the output buffer
        unset($p);
        if (file_exists($page_path) == False) mkdir($page_path, 0765, True);
        file_put_contents($page_filename, ob_get_flush());
    }
}

