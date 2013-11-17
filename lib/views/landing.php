<?php
$p = new BasePage('Home',
                  'Personal website.',
                  header_large($experienceList->types()),
                  footer());

$recent = $experienceList->mostRecent(6);
if (count($recent) > 0) {
    foreach($recent AS $experience) $p->append($experience->displayBox());
    $p->wrap('div', Array('class'=>'c'));
    $p->prepend(h1('Recently added:',array('class'=>'c2 m20 c mainFont')));
}
else {
    $p->h1('This site does not yet have any content.',array('class'=>'c2 m20 mainFont'));
    $p->p('To display content in this website you need to put folders in ' . BASE_PATH);
    $p->wrap('div', Array('class'=>'c'));
}