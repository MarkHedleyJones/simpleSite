<?php
$p = new BasePage('Home', 'Personal website.', header_small(), footer());
$p->add_css_reference( url_static() . '/fancybox/source/jquery.fancybox.css?v=2.1.5');
$p->add_script_reference( url_static() . '/fancybox/source/jquery.fancybox.pack.js?v=2.1.5');
$p->add_postscript('$(document).ready(function() {
	$(".fancybox").fancybox({
		openEffect	: "none",
		closeEffect	: "none",
	});
});');
$location = path_array();
$a = new Content();

$path_arr = path_array();
$category = $path_arr[0];

$experience = new Experience(path_string());

$a->h1($experience->name . ' ' . $experience->date->format('Y'),array('class'=>'c2 mainFont'));
$a->h2($experience->description,array('class'=>'c3 mainFont'));

$experience->get_thumbnails($a);
$experience->populate_files();

$a->wrap('div',array('class'=>'main'));

$p->append($a);