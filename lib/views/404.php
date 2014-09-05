<?php
header("HTTP/1.0 404 Not Found");
$p = new BasePage('Page not found',
                  'This page no-longer exists.',
                  header_large(Array()),
                  footer());

$p->h1('404 - Page not found',array('class'=>'c2 m20 mainFont'));
$p->p('Perhaps there is an error in the url, more likely the content you are looking for has been removed.');
$p->p('Want to see what else is ' . href('/', 'on this site') . '?', Array('class'=>'fLarge p20'));
$p->wrap('div', Array('class'=>'c'));