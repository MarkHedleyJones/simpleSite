<?php
$tests = Array(
    '20130303this is the title'=>Array,
    '20130303this is the title',
    '20130303this is the title',
    '20130303this is the title-description',
    '20130303this is the title_description',
    '20130303this is the title_description',
    '20130303this is the title-description',
    '20130303 this is the title',
    '20130303-this is the title',
    '20130303_this is the title',
    '20130303-this is the title-description',
    '20130303_this is the title_description',
    '20130303-this is the title_description',
    '20130303_this is the title-description',
    '2013-03-03 this is the title',
    '2013-03-03-this is the title',
    '2013-03-03_this is the title',
    '2013-03-03-this is the title-description',
    '2013-03-03_this is the title_description',
    '2013-03-03-this is the title_description',
    '2013-03-03_this is the title-description',
    '2013_03_03 this is the title',
    '2013_03_03-this is the title',
    '2013_03_03_this is the title',
    '2013_03_03-this is the title-description',
    '2013_03_03_this is the title_description',
    '2013_03_03-this is the title_description',
    '2013_03_03_this is the title-description',
    '2013 03 03 this is the title',
    '2013 03 03 this is the title',
    '2013 03 03 this is the title',
    '2013 03 03 this is the title-description',
    '2013 03 03 this is the title_description',
    '2013 03 03 this is the title_description',
    '2013 03 03 this is the title-description',
    '201303this is the title',
    '201303this is the title',
    '201303this is the title',
    '201303this is the title-description',
    '201303this is the title_description',
    '201303this is the title_description',
    '201303this is the title-description',
    '201303 this is the title',
    '201303-this is the title',
    '201303_this is the title',
    '201303-this is the title-description',
    '201303_this is the title_description',
    '201303-this is the title_description',
    '201303_this is the title-description',
    '2013-03-this is the title',
    '2013-03-this is the title',
    '2013-03-this is the title-description',
    '2013-03_this is the title_description',
    '2013-03-this is the title_description',
    '2013-03_this is the title-description',
    '2013_03 this is the title',
    '2013_03-this is the title',
    '2013_03_this is the title',
    '2013_03-this is the title-description',
    '2013_03_this is the title_description',
    '2013_03-this is the title_description',
    '2013_03_this is the title-description',
    '2013 03 this is the title',
    '2013 03 this is the title_description',
    '2013 03 this is the title-description',
    '2013this is the title',
    '2013this is the title',
    '2013this is the title',
    '2013this is the title-description',
    '2013this is the title_description',
    '2013this is the title_description',
    '2013this is the title-description',
    '2013 this is the title',
    '2013-this is the title',
    '2013_this is the title',
    '2013-this is the title-description',
    '2013_this is the title_description',
    '2013-this is the title_description',
    '2013_this is the title-description',
    '2013-this is the title',
    '2013-this is the title-description',
    '2013_this is the title_description',
    '2013-this is the title_description',
    '2013_this is the title-description',
    '2013 this is the title',
    '2013 this is the title_description',
    '2013 this is the title-description');

// function date_fromString($string) {
//     $parts = explode('-', $string);
//     $parts = array_filter($parts, function ($x) {return is_numeric($x); });
//     $date = False;
//     $numParts = count($parts);
//     if ($numParts == 1) {
//         if (strlen($parts[0]) > 4) {
//             // Contains date data non-delimited
//             $str = $parts[0];
//             if (strlen($str) == 6) {
//                 $parts = Array(substr($str, 0, 4),
//                                substr($str, 4, 6));
//             }
//             elseif (strlen($str) == 8) {
//                 $parts = Array(substr($str, 0, 4),
//                                substr($str, 4, 6),
//                                substr($str, 6, 8));
//             }
//             else return False;
//         }
//         else $parts = Array($str);
//     }
//     return array_toDate($parts);
// }

// function array_toDate($array) {
//     $numParts = count($array);
//     if ($numParts > 0 && $numParts < 4) {
//         if ($numParts == 1) {
//             $date = new DateTime($parts[0] . '-1-1 0:0:0');
//             $formatter = 'Y';
//         }
//         elseif ($numParts == 2) {
//             $date = new DateTime($parts[0] . '-' . $parts[1] . '-1 0:0:0');
//             $formatter = 'M Y';
//         }
//         else {
//             $date = new DateTime($parts[0] . '-' . $parts[1] . '-' . $parts[2] . ' 0:0:0');
//             $formatter = 'jS M Y';
//         }
//         return Array('date'=>$date,
//                      'formatter'=>$formatter);
//     }
//     else return False;
// }

function unpack_filename($filename) {
    $out = Array();
    $lastDigitPos = 0;
    $strlen = strlen($filename);
    for ($i = 0; $i < $strlen; $i++) {
        if (is_numeric($filename[$i])) $lastDigitPos = $i + 1;
    }
    $date = substr($filename, 0, $lastDigitPos);
    $date = str_replace('-', '', $date);
    $date = str_replace('_', '', $date);
    $date = str_replace(' ', '', $date);
    $datelen = strlen($date);
    if ($datelen == 8) {
        if (intval(substr($date, 0, 4)) > 1970) {
            $out['year'] = substr($date, 0, 4);
            $out['month'] = substr($date, 4, 2);
            $out['day'] = substr($date, 6, 2);
        }
        elseif (intval(substr($date, 4, 8)) > 1970) {
            $out['day'] = substr($date, 0, 2);
            $out['month'] = substr($date, 2, 2);
            $out['year'] = substr($date, 4, 4);
        }
    }
    elseif ($datelen == 6) {
        if (intval(substr($date, 0, 4)) > 1970) {
            $out['year'] = substr($date, 0, 4);
            $out['month'] = substr($date, 4, 2);
        }
        elseif (intval(substr($date, 2, 6)) > 1970) {
            $out['month'] = substr($date, 0, 2);
            $out['year'] = substr($date, 2, 2);
        }
    }
    elseif ($datelen == 4) $out['year'] = substr($date, 0, 4);

    //Get title-description from $rest
    $rest = substr($filename, $lastDigitPos);

    while (strpos($rest, '-') === 0) $rest = substr($rest, 1);
    while (strpos($rest, '_') === 0) $rest = substr($rest, 1);
    print $rest . '<br>';
    $parts = explode('-', $rest);
    if (count($parts) == 1) {
        $parts = explode('_', $rest);
        if (count($parts) == 1) $out['title'] = $rest;
        else {
            $out['title'] = reset($parts);
            $out['description'] = $parts[1];
        }
    }
    else {
        $out['title'] = reset($parts);
        $out['description'] = $parts[1];
    }
    return $out;
}

foreach($tests AS $test) {
    print $test;
    print '<br>';
    print_r(unpack_filename($test));
    print '<br><br>';
}