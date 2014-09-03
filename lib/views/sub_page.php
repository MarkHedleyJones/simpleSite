<?php
$type = str_replace('/', '', url_string());
$description = "The " . $type . " page has been automatically generated using SimpleSite. This software is freely available on GitHub (github.com/markjones112358/simpleSite). Putting a plain text file in this folder (titled &rsquo;description&rsquo;) will overwrite this message with its contents.";
$files = scan_filesByExtensions(PATH_WATCH . '/' . $type, 'txt');
foreach ($files as $file) {
    if (strpos(strtolower($file), 'desc') !== False) {
        echo "here<br>";
        $description = retrieve_and_clean(PATH_WATCH . '/' . $type . '/' . $file, 160);
    }
}
$p = new BasePage(ucfirst($type),
                  $description,
                  header_small(),
                  footer());
$recent = ExperienceList::ordered_byDate($experienceList->experiences[$type]);
foreach($recent AS $experience) $p->append($experience->displayBox());
$p->wrap('div', Array('class'=>'c', 'style'=>'margin-bottom: 140px'));
$p->prepend(h1(ucfirst($type),array('class'=>'c2 m20 c mainFont')));