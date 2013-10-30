<?php
$p = new BasePage('Home', 'Personal website.');
$c = new Content();

// Create the frontpage header
$h = new Content();
$h->append(div(NAME_OF_SITE, array('class'=>'title c_l1')));
$h->append(div(sitename(), array('class'=>'subtitle c_l3')));
$h->wrap('div', array('class'=>'right mainFont'));
$h->prepend(img( url_static() . '/identicon_210.png','Identicon'));
$h->wrap('div', array('class'=>'header'));
$h->wrap('div', array('class'=>'centerwrap'));
$c->append($h);

// Create center navigation strip
$n = new Content();
$path = path_string();
$dirs = get_subdirs($path);

$list = new UnorderedList(array('class'=>'c_l1'));
foreach ($dirs as $dir) {
	
	$list->append(href(ucfirst(substr($dir,1)),$dir));
}
$n->append($list);
$n->wrap('div', array('class'=>'navbanner mainFont bg_l3'));
$c->append($n);


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

$c->append($a);
$p->append($c);