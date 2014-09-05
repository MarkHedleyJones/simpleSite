<?php

// Site settings
date_default_timezone_set('Pacific/Auckland');
define('NAME_OF_SITE','My Website');
define('TAGLINE', 'About me');
define('FOOTER_TEXT', 'Source code for this site available from <a class="c1" href="https://github.com/markjones112358/simpleSite">github.com/markjones112358/simpleSite</a>');
define('MAINPAGE_SUBTITLE', 'Recently added...');

// Logo names - Logos live in /static
define('LOGO_LARGE','logo_large.png');
define('LOGO_SMALL','logo_small.png');

// Colour theme:
define('COLOR_0', '#000000');
define('COLOR_1', '#3c3c3c');
define('COLOR_2', '#696969');
define('COLOR_3', '#c3c3c3');
define('COLOR_4', '#f0f0f0');
define('COLOR_5', '#ffffff');
define('COLOR_x', '#e6e6e6'); // By default is used for experience tag handles

/************************** Google Analytics ***************************/

// Want to use Google Analytics to see how many people are visiting your site?
//
// Uncomment the following two lines and add the required strings from google.
//
// For the verificationn string - add only the contents of the content field in
// the meta tag (don't verify by uploading the file).
//
// For the tracking code - remove the script tags from the provided tracking
// snippit.

// define('GOOGLE_SITE_VERIFICATION', "");
// define('GOOGLE_TRACKING_CODE',"");


/*********************** Custom experience boxes ***********************/

// The code below will be executed to create the boxes on the home
// and subfolder index pages. It is passed the relevant details for the
// experience and returns PHPified HTML code (see PHP-HTMLifier).
// Alternatively you can return a straight HTML string.
// This function can be uncommented and edited here (in this file) and
// it will be used within the site.
//
// $title: Name of the post/blog/experience
// $description: Contents of the desc.txt or description.txt file within the
//               experience's folder
// $thumbnail: Path to the thumbnail for the given experience
// $url: Path to the experience page
//
// function user_displayBox($title, $description, $thumbnail, $url, $date) {
//     $box = new Content();
//     $box->span($title,
//                array(
//                      'class'=>'c5',
//                      'style'=>'display: inline-block; width: 100%'
//                      )
//                );
//     $box->img($thumbnail, $title);
//     return href($url,
//                 $box,
//                 array(
//                     'href' => $url,
//                     'class' => 'expBox mainFont bg_c4')
//                 );
// }