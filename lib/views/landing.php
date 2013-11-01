<?php
$p = new BasePage('Home', 'Personal website.', header_large(), footer());

$a = new Content();
$a->append(h2('Recently added:',array('class'=>'c_l2 mainFont')));

$path = path_string();
$subdirs = get_subdirs($path);

$experiences = array();
foreach ($subdirs as $experienceType) {
	$experiencesOfThisType = get_subdirs($experienceType);
	foreach ($experiencesOfThisType as $experience) {
		array_push($experiences,new Experience($experience));
	}
}

$experiences = Experience::sorted($experiences);
$experiences = array_splice($experiences, 0, 3);

foreach ($experiences as $experience) $a->append($experience->displayBox());
$a->wrap('div',array('class'=>'main'));

$p->append($a);