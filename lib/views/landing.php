<?php
$p = new BasePage(False,
                  False,
                  header_large($experienceList->types()),
                  footer());

$recent = $experienceList->mostRecent(12);
if (count($recent) > 0) {
    foreach($recent AS $experience) $p->append($experience->displayBox());
    $p->wrap('div', Array('class'=>'c', 'style'=>'margin-bottom: 140px'));
    $p->prepend(h2('Recently updated...',array('class'=>'c3 m20 c mainFont fLarger')));
}
else {
    $p->h2('This site does not yet have any content.',array('class'=>'c2 m20 mainFont'));
    $p->p('To display content in this website you need to put folders in ' . PATH_WATCH);
    $p->wrap('div', Array('class'=>'c'));
}