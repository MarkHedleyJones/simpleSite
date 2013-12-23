<?php
$p = new BasePage('Home',
                  'Personal website.',
                  header_large($experienceList->types()),
                  footer());

$recent = $experienceList->mostRecent(6);
if (count($recent) > 0) {
    foreach($recent AS $experience) $p->append($experience->displayBox());
    $p->wrap('div', Array('class'=>'c'));
    $p->prepend(h2('Most recently added:',array('class'=>'c3 m20 c mainFont fLarger')));
}
else {
    $p->h2('This site does not yet have any content.',array('class'=>'c2 m20 mainFont'));
    $p->p('To display content in this website you need to put folders in ' . PATH_WATCH);
    $p->wrap('div', Array('class'=>'c'));
}