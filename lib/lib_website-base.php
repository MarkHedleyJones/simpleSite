<?php

function sitename() {
	return $_SERVER['HTTP_HOST'];

}

function url_content() {
	return 'http://' . $_SERVER['HTTP_HOST'];
}

function url_static() {
	return 'http://' . URL_STATIC;
}

function first($num, $array) {
    return array_slice($array, 0, $num);
}

function last($num, $array) {
    return array_slice($array, -1 * $num);
}

function header_large($navLinks) {

	$c = new Content();

	// Create the frontpage header
	$h = new Content();
	$h->h1(NAME_OF_SITE, array('class'=>'title c1 m0'));
	$h->append(div(TAGLINE, array('class'=>'subtitle c3')));
	$h->wrap('div', array('class'=>'right mainFont'));
	$h->prepend(href(img( url_static() . '/' . LOGO_LARGE,'Site logo'),'/'));
	$h->wrap('div', array('id'=>'head'));
	$c->append($h);

	// Create center navigation strip
	$n = new Content();

	$list = new UnorderedList(array('class'=>'c_l1'));
	foreach ($navLinks as $link) {
		$list->append(href(ucfirst($link),'/' . $link));
	}
	$n->append($list);
	$n->wrap('div', array('class'=>'navbanner mainFont bg_c3 bdr_c275 a_c2 ahover_c1'));

	$c->append($n);
	$c->wrap('div', array('id'=>'header', 'class'=>'bg_c4'));
	return $c;
}

function header_small() {
	$location = url_array();

	$c = new Content();

	// Build the header
	$h = new Content();
	$h->append(div(NAME_OF_SITE, array('class'=>'title c1')));
	$h->append(div(TAGLINE, array('class'=>'subtitle c3')));
	$h->wrap('div', array('class'=>'right mainFont'));
    $h->prepend(href(img( url_static() . '/' . LOGO_SMALL,'Site logo'),'/'));
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
    public $navLinks;

    public function __construct($title=False,
                                $description=False,
                                $header=False,
                                $footer=False) {
        if ($title == False ) $title = sitename();
        else $title .= ' | ' . sitename();
        parent::__construct($title, $description);
        $this->header = $header;
        $this->footer = $footer;
    }

    public function __destruct() {
    	$this->wrap('div', array('id'=>'content'));
    	if ($this->header) $this->prepend($this->header);
    	if ($this->footer) $this->append($this->footer);
        if (defined('GOOGLE_SITE_VERIFICATION')) {
            $this->generic_tag('meta',
                               Array('name' => 'google-site-verification',
                                               'content'=> GOOGLE_SITE_VERIFICATION));
        }
        $this->style_reference( url_static() . '/style.css');
        $this->style_reference( url_static() . '/theme.php');
        $this->style_reference('http://fonts.googleapis.com/css?family=Merriweather+Sans:400,700');
        if (defined('GOOGLE_TRACKING_CODE')) {
            $this->script_block(GOOGLE_TRACKING_CODE);
        }
        parent::render();
    }
}

function last_modified($path) {
    return exec('find ' . $path . ' -exec stat \{} --printf="%Y\n" \; | sort -n -r | head -n 1');
}

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


function url2path($url, $debug=False) {
    $out = str_replace('//','/', PATH_WATCH . $url);
    if ($debug) print '<br><br>url2path url = "' . $url . '" -> path = ' . $out;
    return $out;
}

function path2url($dir, $debug=False) {
    $out = clean_path((str_replace(PATH_WATCH, '/', $dir)));
    if ($debug) print '<br><br>path2url path = "' . $dir . '" -> url = ' . $out;
    return $out;
}

function clean_path($path, $debug=False) {
    if ($debug) print '<br><br>clean_path called with ' . $path;
    $out = str_replace('//', '/', $path);
    if ($debug) print '<br>return = ' . $out;
    return $out;
}

function url_string() {
    $out = $_SERVER['REQUEST_URI'];
    if (substr($out, -1) != '/') return $out . '/';
    else return $out;
}

function path_string() {
    return url2path(url_string());
}

function get_subdirs($path,$debug=False) {
    if ($debug) print '<br><br>get_subdirs called with with "' . $path . '"';
    $path = clean_path($path);
    $realPath = url2path($path);

    $subdirs = array_map(function ($x) {return $x . '/';}, array_filter(scandir($realPath), function($x) {return $x[0] != '.';}));
    if ($debug) {
        print '<br>subdirs = ';
        print_r($subdirs);
    }

    $urlSubdirs = array();
    if ($debug) print '<br>entering loop:';
    foreach ($subdirs as $subdir) {
        if ($debug) print '<br>scanning subdir = "' . $subdir . '"';
        $realSubdir = url2path($path . $subdir);
        if ($debug) print '<br>realsubdir = "' . $realSubdir . '"';

        if ($subdir[0] != '.' && is_dir($realSubdir)) {
            if ($debug) print '<br>pushed ' . $realSubdir;
            array_push($urlSubdirs, path2url($realSubdir));
        }
        else {
            if ($debug) print '<br>not added (' . $realSubdir . ')';
        }
    }
    if ($debug) {
        print '<br>exited loop, returning';
        print_r($urlSubdirs);
    }

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
    $full = url_content() . '/';
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


/**
 * Returns an array containing the current folder path
 * @return multitype:
 */
function url_array($debug=False) {
    if ($debug) print '<br>path_array requested';
    $url = url_string();
    if ($debug) print '<br>url = ' . $url;
    $path = explode('/', $url);
    if ($debug) {
        print '<br>raw path array = ';
        print_r($path);
    }
    $out = array_slice($path,1, count($path)-2);
    if ($debug) {
        print '<br>output array = ';
        print_r($out);
    }
    return $out;
}

function urlToarray($url) {
    if ($url == False) {
        return Array();
    }
    else {
        $path_base = trim($url);
        return array_filter(explode('/', $path_base), function ($x) {return strlen($x) > 0; });
    }
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
