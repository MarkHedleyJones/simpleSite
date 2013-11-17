<?php
$p = new BasePage('Home',
                  'Personal website.',
                  header_large($experienceList->types()),
                  footer());

$recent = $experienceList->mostRecent(6);
foreach($recent AS $experience) $p->append($experience->displayBox());
$p->wrap('div', Array('class'=>'c'));
$p->prepend(h1('Recently added:',array('class'=>'c2 m20 c mainFont')));