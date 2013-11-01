<?php
/*
 * Takes all incoming page requests and loads in the appropriate page.
 * This page loads the master reference to /lib/base that each of the views
 * make reference of.
 */

include(dirname($_SERVER['DOCUMENT_ROOT']).'/lib/base.php');

$request = $_SERVER['REQUEST_URI'];
$path = explode('/', $request, -1);
$depth = count($path);

if ($depth == 1) include("../lib/views/landing.php");
elseif ($depth == 2) include("../lib/views/sub_page.php");
elseif ($depth == 3) include("../lib/views/sub_sub_page.php");