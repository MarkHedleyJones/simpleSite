<?php
include(dirname($_SERVER['DOCUMENT_ROOT']).'/lib/base.php');

$request = $_SERVER['REQUEST_URI'];
$path = explode('/', $request, -1);
$depth = count($path);

if ($depth == 1) include("index_root.php");
elseif ($depth == 2) include("index_sub.php");
elseif ($depth == 3) include("index_sub_sub.php");