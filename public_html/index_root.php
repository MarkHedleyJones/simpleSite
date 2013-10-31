<?php
$p = new BasePage('Home', 'Personal website.', header_large());

$a = new Content();
$a->append(h2('Recent activity:',array('class'=>'c_l2 mainFont')));

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

// Display items
foreach ($experiences as $experience) $a->append($experience->displayBox());
$a->wrap('div',array('class'=>'main'));
$a->wrap('div',array('class'=>'mainwrap'));

$p->append($a);
