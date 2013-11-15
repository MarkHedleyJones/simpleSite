<?php

define('ROOT_NAME',$_SERVER['HTTP_HOST']);
define('ROOT_URL', 'http://' . $_SERVER['HTTP_HOST']);


function sitename() {
	$parts = explode('.',$_SERVER['HTTP_HOST']);
	return $parts[1];
}

function tld() {
	return str_replace('www.' . sitename(), '', $_SERVER['HTTP_HOST']);
}

function url_www() {
	return 'http://www.' . sitename() . tld();
}

function url_static() {
	return 'http://static.' . sitename() . tld();
}

function header_large() {

	$c = new Content();

	// Create the frontpage header
	$h = new Content();
	$h->append(div(NAME_OF_SITE, array('class'=>'title c1')));
	$h->append(div(sitename(), array('class'=>'subtitle c3')));
	$h->wrap('div', array('class'=>'right mainFont'));
	$h->prepend(img( url_static() . '/identicon_210.png','Identicon'));
	$h->wrap('div', array('id'=>'head'));
	$c->append($h);

	// Create center navigation strip
	$n = new Content();
	$path = path_string();
	$dirs = get_subdirs($path);

	$list = new UnorderedList(array('class'=>'c_l1'));
	foreach ($dirs as $dir) {
		$list->append(href(ucfirst(substr($dir,1)),$dir));
	}
	$n->append($list);
	$n->wrap('div', array('class'=>'navbanner mainFont bg_c3 bdr_c2 a_c2 ahover_c1'));

	$c->append($n);
	$c->wrap('div', array('id'=>'header', 'class'=>'bg_c4'));
	return $c;
}

function header_small() {
	$location = path_array();

	$c = new Content();

	// Build the header
	$h = new Content();
	$h->append(div(NAME_OF_SITE, array('class'=>'title c1')));
	$h->append(div(sitename(), array('class'=>'subtitle c3')));
	$h->wrap('div', array('class'=>'right mainFont'));
	$h->prepend(img( url_static() . '/identicon_105.png','Identicon'));
	$h->wrap('div', array('id'=>'head', 'class'=>'mini'));
	$c->append($h);


	// Create center navigation strip
	$n = new Content();
	formatPathURL($location, $n);
	$n->wrap('div', array('class'=>'navbanner mainFont bg_c3 bdr_c2'));
	$c->append($n);
	$c->wrap('div', array('id'=>'header', 'class'=>'bg_c4'));
	return $c;
}

function footer() {
	$c = new Content();
	$c->span(FOOTER_TEXT, array('class'=>'mainFont'));
	$c->wrap('div', array('id' => 'footer',
						  'class' => 'bg_c3 bdr_c2 c2 fSmaller'));
	return $c;
}

class BasePage extends Page {

	public $header;
	public $footer;

    public function __construct($title, $description, $header=False, $footer=False) {
        parent::__construct($title . ' | ' . sitename(), $description);
        $this->header = $header;
        $this->footer = $footer;
    }

    public function __destruct() {
    	$this->wrap('div', array('id'=>'content'));
    	if ($this->header) $this->prepend($this->header);
    	if ($this->footer) $this->append($this->footer);
    	$this->add_script_reference('http://code.jquery.com/jquery-latest.min.js');
        $this->add_css_reference( url_static() . '/style.css');
        $this->add_css_reference( url_static() . '/theme.php');
        $this->add_css_reference('http://fonts.googleapis.com/css?family=Merriweather+Sans:400,700');
        parent::render();
    }
}

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

function get_filesInDir($path, $extension='jpg') {
    return array_filter(scandir($path), function($x) use($extension) {
        if (strpos(strtolower($x), '.' . $extension) != False) return True;
        else return False;
    });
}

function get_photosInDir($path) {
    return array_filter(scandir($path), function($x) {
        if (strpos(strtolower($x),'.jpg') != False) return True;
        else return False;
    });
}


function url2path($url) {
    return substr($_SERVER['DOCUMENT_ROOT'],0, count($_SERVER['DOCUMENT_ROOT']) - 2)  . $url;
}

function path2url($dir) {
    return str_replace($_SERVER['DOCUMENT_ROOT'], '/', $dir);
}

function get_subdirs($path,$debug=False) {
    if ($debug) print '<br>called with path = ' . $path . '<br>';
    $realPath = url2path($path);
    if ($debug) print 'realpath = ' . $realPath . '<br>';
    $subdirs = scandir($realPath);
    $urlSubdirs = array();
    if ($debug) print '<br><br>enteringloog<br><br>';
    foreach ($subdirs as $subdir) {
        if ($debug) print 'subdir = ' . $subdir . '<br>';
        $realSubdir = url2path($path . '/' . $subdir);
        if ($debug) print 'realsubdir = ' . $realSubdir . '<br>';

        if ($subdir[0] != '.' && is_dir($realSubdir)) {
            if ($debug) print 'pushed this !!!!!<br>';
            array_push($urlSubdirs, path2url($realSubdir));
        }
        else {
            if ($debug) print 'not adding this <br>';
        }
    }
    if ($debug) print 'returning ';
    if ($debug) print '<br>';
    return $urlSubdirs;
}

function strip_underscores($string) {
    return str_replace('_', ' ', $string);
}

function unpack_directory($directory) {
    $parts = explode('-',$directory);
    $out = array();
    foreach($parts AS $part) {
        array_push($out, strip_underscores($part));
    }
    return $out;
}


function decomposeAlbumName() {
    $pathName = $_SERVER['REQUEST_URI'];
    $folderName = explode('/', $pathName);
    $folderName = $folderName[2];
    $parts = explode('-',$folderName);
    return $parts;
}


/**
 * Inserts a formatted url path on the page for navigation.
 * @param [ARRAY] $path - A directory path to the current location
 * @param [HTML ELEMENT] $element - The page object to which the path is added
 */
function formatPathURL($path, $element) {
    $full = 'http://' . ROOT_NAME . '/';
    $len = count($path);
    $element->href(sitename(),$full);
    if ($len > 0) {
        $element->span('/');
    }
    foreach (array_values($path) as $i => $tree) {
        $full .= $tree . '/';
        if ($i == $len - 1) $element->b(str_replace('_', ' ', $tree));
        else {
            $element->href(str_replace('_', ' ', $tree),$full);
            $element->span('/');
        }
    }
    $element->br();
}

function path_string() {
	return substr($_SERVER['REQUEST_URI'],0,-1);
	#return substr($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'],0,-1);
    #return str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
}


/**
 * Returns an array containing the current folder path
 * @return multitype:
 */
function path_array() {
    $url = $_SERVER['REQUEST_URI'];
    $path = explode('/', $url);
    $out = array_slice($path,1, count($path)-2);
    return $out;
}


function getDirs($path) {
    echo 'called with ' . $path . '<br>';
    $subdirs = array();
    foreach(scandir($path) AS $dir) {
        if (is_dir($dir) && $dir != '.' && $dir != '..') array_push($subdirs,array($dir -> array()));
    }
    $out = array();
    foreach ($subdirs AS $subdir) {
        $nsub = getDirs($path .'/' . $subdir);
        if (count($nsub)>0) {
            $out[$subdir] = $nsub;
        }
        else {
            $out[$subdir] = False;
        }
    }
    echo 'returning';
    return $out;
}

function photoLink($photoPath, $caption, $href, $landscape=True) {
    return block('div', href(img($photoPath.'_small.JPG', $caption), $href) . p($caption), array('class'=>'polaroid '.($landscape ? '' : 'portrait')));
}

function photo($filename, $caption, $landscape=True) {
    return block('div', href(img($_SERVER['REQUEST_URI'] .$filename.'_small.JPG', $caption), $_SERVER['REQUEST_URI'] .$filename.'.JPG') . p($caption), array('class'=>'polaroid '.($landscape ? '' : 'portrait')));
}

function addPhoto($filename, $caption, $landscape=True) {
    echo photo($filename, $caption, $landscape);
}

function addPhotos($photoArr) {
    foreach ($photoArr as $filename => $caption) {
        addPhoto($filename, $caption);
    }
}

function filter_relativeDirs($var) {
    if ($var == '.') return false;
    if ($var == '..') return false;
    if (is_dir($var) == true) return true;
}
