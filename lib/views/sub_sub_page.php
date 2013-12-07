<?php
$p = new BasePage('Home',
                  'Personal website.',
                  header_small(),
                  footer());

$p->style_reference( url_static() . '/fancybox/source/jquery.fancybox.css?v=2.1.5');
$p->script_reference( url_static() . '/fancybox/source/jquery.fancybox.pack.js?v=2.1.5');
$p->script_block('$(document).ready(function() {
    $(".fancybox").fancybox({
        openEffect  : "none",
        closeEffect : "none",
    });
});');

$type = reset(url_array());
$name = end(url_array());
$experience = $experienceList->get_experience($type, $name);
$p->append($experience->render());
$p->wrap('div', Array('class'=>'c'));

$p->prepend(h1(ucfirst($experience->title),array('class'=>'c2 m20 c mainFont')));
