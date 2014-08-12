<?php
$type = str_replace('/', '', url_string());
$p = new BasePage(ucfirst($type),
                  'Personal website.',
                  header_small(),
                  footer());
$recent = ExperienceList::ordered_byDate($experienceList->experiences[$type]);
foreach($recent AS $experience) $p->append($experience->displayBox());
$p->wrap('div', Array('class'=>'c', 'style'=>'margin-bottom: 140px'));
$p->prepend(h1(ucfirst($type),array('class'=>'c2 m20 c mainFont')));