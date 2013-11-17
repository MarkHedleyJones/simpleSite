<?php
$p = new BasePage('Home',
                  'Personal website.',
                  header_small(),
                  footer());
$type = str_replace('/', '', url_string());

$recent = ExperienceList::ordered_byDate($experienceList->experiences[$type]);
foreach($recent AS $experience) $p->append($experience->displayBox());
$p->wrap('div', Array('class'=>'c'));
$p->prepend(h1(ucfirst($type),array('class'=>'c2 m20 c mainFont')));