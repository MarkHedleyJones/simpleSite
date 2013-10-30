<?php

$p = new ExperiencePage();
$c = $p->content();

$location = path_array();
$a = new Content();

$path_arr = path_array();
$category = $path_arr[0];

$experience = new Experience(path_string());

$a->h1($experience->name . ' ' . $experience->date->format('Y'),array('class'=>'c_l1'));
$a->h2($experience->description,array('class'=>'c_l2'));

$experience->get_thumbnails($a);

$a->wrap('div',array('class'=>'main'));
$a->wrap('div',array('class'=>'mainwrap'));
$c->append($a);