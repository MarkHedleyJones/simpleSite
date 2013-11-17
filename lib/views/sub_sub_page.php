<?php
$p = new BasePage('Home',
                  'Personal website.',
                  header_small(),
                  footer());

$p->add_css_reference( url_static() . '/fancybox/source/jquery.fancybox.css?v=2.1.5');
$p->add_script_reference( url_static() . '/fancybox/source/jquery.fancybox.pack.js?v=2.1.5');
$p->add_postscript('$(document).ready(function() {
    $(".fancybox").fancybox({
        openEffect  : "none",
        closeEffect : "none",
    });
});');

$type = reset(url_array());
$name = end(url_array());
$experience = $experienceList->get_experience($type, $name);
$p->append($experience->render());
$p->wrap('div', Array('class'=>'c'));

$p->prepend(h1(ucfirst($experience->title),array('class'=>'c2 m20 c mainFont')));

die();

$location = path_array();
$a = new Content();

$path_arr = path_array();
$category = $path_arr[0];

$experience = new Experience(path_string());

$title = $experience->title;
if (isset($experience->date)) $title .= ' - ' . $experience->date->format($experience->dateFormatter);

$a->h1($title,
       array('class'=>'c2 mainFont'));

$a->h2($experience->description,array('class'=>'c3 mainFont'));

$experience->get_thumbnails($a);
$experience->populate_files();

$a->wrap('div',array('class'=>'main'));

$p->append($a);