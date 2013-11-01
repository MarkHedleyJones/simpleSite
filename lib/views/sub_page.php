<?php
$p = new BasePage('Home', 'Personal website.', header_small(), footer());

$location = path_array();
$a = new Content();
$a->append(h2(ucfirst($location[count($location)-1]).':',array('class'=>'c_l2 mainFont')));

// Collect and sort the list of albums
$experiences = array();
foreach (get_subdirs(path_string()) as $pathname) {
	array_push($experiences, new Experience($pathname));
}

$experiences = Experience::sorted($experiences);

foreach ($experiences as $experience) $a->append($experience->displayBox());
$a->wrap('div',array('class'=>'main'));

$p->append($a);