<?php

$type = reset(url_array());
$name = end(url_array());
$experience = $experienceList->get_experience($type, $name);

$p = new BasePage($name . ' | ' . ucfirst($type),
                  $experience->description,
                  header_small(),
                  footer());

$p->style_reference( url_static() . '/fancybox/source/jquery.fancybox.css?v=2.1.5');
$p->script_reference('http://code.jquery.com/jquery-latest.min.js');
$p->script_reference( url_static() . '/fancybox/source/jquery.fancybox.pack.js?v=2.1.5');
$p->readyScript('$(document).ready(function() {
    $(".fancybox").fancybox({
        openEffect  : "none",
        closeEffect : "none",
    });
});');
$p->append($experience->render());
$p->prepend(hr());
$p->prepend(h1(ucfirst($experience->title),array('class'=>'c2 m20 c mainFont')));