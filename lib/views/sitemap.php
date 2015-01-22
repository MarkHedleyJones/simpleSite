<?php
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
$experienceList = new ExperienceList('/');
$experiences = ExperienceList::ordered_byDate($experienceList->get_all());
xml_url("http://" . $_SERVER['HTTP_HOST']);

$subs = array();
foreach ($experiences AS $experience) {
    $a = explode('/',$experience->url);
    $a = array_filter($a, function ($x) { return $x !== "";});
    if (in_array($a[1], $subs) == False) array_push($subs, $a[1]);
}

foreach ($subs AS $sub) {
    xml_url("http://" . clean_path($_SERVER['HTTP_HOST'] . '/' . $sub . '/'));
}

foreach ($experiences AS $experience) {
    xml_url("http://" . clean_path($_SERVER['HTTP_HOST'] . '/' . substr($experience->url,0,-1)));
}

echo "</urlset>\n";

function xml_url($url) {
    echo "\t<url>\n";
    echo "\t\t<loc>" . $url . "</loc>\n";
    echo "\t</url>\n";
}