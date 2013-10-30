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


class ExperiencePage extends Page {

	public function __construct() {
		$location = path_array();
		$title = ucfirst($location[count($location)-1]);
		$description = 'This is my personal website.';
		parent::__construct($title . ' | ' . sitename(), $description);

		$c = new Content();

		// Build the header
		$h = new Content();
		$h->append(div(NAME_OF_SITE, array('class'=>'title c_l1')));
		$h->append(div(sitename(), array('class'=>'subtitle c_l3')));
		$h->wrap('div', array('class'=>'right mainFont'));
		$h->prepend(img( url_static() . '/identicon_105.png','Identicon'));
		$h->wrap('div', array('class'=>'header mini'));
		$h->wrap('div', array('class'=>'centerwrap'));
		$c->append($h);


		// Create center navigation strip
		$n = new Content();
		formatPathURL($location, $n);
		$n->wrap('div', array('class'=>'navbanner mainFont bg_l3'));
		$c->append($n);
		$this->c = $c;
	}

	public function __destruct() {
		$this->append($this->c);
        $this->add_script_reference('http://code.jquery.com/jquery-latest.min.js');

		$this->add_script_reference( url_static() . '/fancybox/lib/jquery.mousewheel-3.0.6.pack.js');		
		$this->add_css_reference( url_static() . '/style.css');
		$this->add_css_reference( url_static() . '/fancybox/source/jquery.fancybox.css?v=2.1.5');
		$this->add_script_reference( url_static() . '/fancybox/source/jquery.fancybox.pack.js?v=2.1.5');
		$this->add_css_reference('http://fonts.googleapis.com/css?family=Merriweather+Sans:400,700');
		$this->add_postscript('$(document).ready(function() {
	$(".fancybox").fancybox({
		openEffect	: "none",
		closeEffect	: "none",
	});
});');
		$this->menu();
		parent::render();
	}

	public function content() {
		return $this->c;
	}


	public function menu() {
		$root = $GLOBALS['_SERVER']['DOCUMENT_ROOT'];
	}
}

class CategoryPage extends Page {

    public function __construct() {
        $location = path_array();
        $title = ucfirst($location[count($location)-1]);
        $description = 'This is my personal website.';
        parent::__construct($title . ' | ' . sitename(), $description);

        $c = new Content();

        // Build the header
        $h = new Content();
        $h->append(div(NAME_OF_SITE, array('class'=>'title c_l1')));
        $h->append(div(sitename(), array('class'=>'subtitle c_l3')));
        $h->wrap('div', array('class'=>'right mainFont'));
        $h->prepend(img( url_static() . '/identicon_105.png','Identicon'));
        $h->wrap('div', array('class'=>'header mini'));
        $h->wrap('div', array('class'=>'centerwrap'));
        $c->append($h);


        // Create center navigation strip
        $n = new Content();
        formatPathURL($location, $n);
        $n->wrap('div', array('class'=>'navbanner mainFont bg_l3'));
        $c->append($n);
        $this->c = $c;
    }

    public function __destruct() {
        $this->append($this->c);
        $this->add_script_reference('http://code.jquery.com/jquery-latest.min.js');
        $this->add_css_reference( url_static() . '/style.css');
        $this->add_css_reference('http://fonts.googleapis.com/css?family=Merriweather+Sans:400,700');
        $this->menu();
        parent::render();
    }

    public function content() {
        return $this->c;
    }


    public function menu() {
        $root = $GLOBALS['_SERVER']['DOCUMENT_ROOT'];
    }
}

class BasePage extends Page {

    public function __construct($title, $description) {
        parent::__construct($title . ' | ' . sitename(), $description);
    }

    public function __destruct() {
        $this->add_script_reference('http://code.jquery.com/jquery-latest.min.js');
        $this->add_css_reference( url_static() . '/style.css');
        $this->add_css_reference('http://fonts.googleapis.com/css?family=Merriweather+Sans:400,700');
        parent::render();
    }
}


class Experience {

    public function __construct($url) {
        $this->url = $url;
        $this->path = url2path($url);
        $tmp = unpack_path($url);
        $this->dir = $tmp[count($tmp) - 1];
        $vars = unpack_directory($this->dir);
        if (count($vars) > 1) {
	        $this->year = $vars[0];
	        $this->month = $vars[1];
	        $this->date = new DateTime($vars[0] . '-' . $vars[1] . '-1 0:0:0');
	        $this->name = $vars[2];
	        $this->description = $vars[3];
	        $this->thumbnail = False;
	        $this->images = False;
        }
        else {
        	$this->name = $vars[0];
        	$this->description = '';
        	$this->thumbnail = False;
        	$this->images = False;
        }
    }

    public static function comaprer($a, $b) {
    	if (isset($a->year) && isset($b->year)) {
	        if ($a->year < $b->year) return True;
	        elseif ($a->year > $b->year) return False;
	        elseif (isset($a->month) && isset($b->month)) {
	        	if ($a->month < $b->month) return True;
	        	else return False;
	        }
	        elseif (isset($a->month)) return False;
	        elseif (isset($b->month)) return True;
    	}
    	elseif (isset($a->year)) return False;
    	elseif (isset($a->year)) return True;
	    return False;
    }

    public static function sorted($input) {
        usort($input, 'Experience::comaprer');
        return $input;
    }
    
    public function get_images() {
    	if ($this->images == False) {
	    	$this->images = array_map(
	    		function ($x) {
	    			return str_replace('_thumb.jpg', '', $x);
	    		},array_filter(get_photosInDir($this->path),
	    		function($x) {
	    			return strpos($x,'_thumb.jpg') != False;
	    		}));
    	}
    	return $this->images;
    }
    
    public function create_imageThumbnail($image) {
    	$box = new Content();
    	$class = 'fancybox expBox';
    	if (strpos($image,'_p') != False) $class .= ' portrait';
    	$wrapAttrs = array('href' => $this->url . '/' . $image . '_main.jpg',
    			'class' => $class,
    			'rel' => 'gallery',
    			'title' => 'test');
    	$box->append(img( url_www() . $this->url . '/' . $image . '_thumb.jpg',""));
    	$box->wrap('a',$wrapAttrs);
    	
    	return $box;
    }
    
    public function get_thumbnails($element) {
    	$images = $this->get_images();
    	$out = array();
    	foreach ($images as $image) {
    		$element->append($this->create_imageThumbnail($image));
    	}
    }

    public function get_thumbnail() {
        if ($this->thumbnail != False) return $this->thumbnail;
        else {
            $thumbPath = $this->path.'/thumb_l.jpg';
            if (is_file($thumbPath)) {
                $this->thumbnail = path2url($thumbPath);
                return $this->thumbnail;
            }
            else {
                $thumbPath = $this->path.'/thumb_p.jpg';
                if (is_file($thumbPath)) {
                    $this->thumbnail = path2url($thumbPath);
                    return $this->thumbnail;
                }
            }

            $photos = get_photosInDir($this->path);
            $photos = array_filter($photos,
                    function ($x) {return strpos($x,'_thumb.jpg') !== False;});
            if (count($photos) > 0) {
            	

                // Pick an image randomly from this directory
                $newThumb = $photos[array_rand($photos,1)];

                // Set the new thumbnail links name according to orientation
                $thumbName = $this->path . '/thumb_p.jpg';
                if (strpos($newThumb, '_p_') != -1) {
                    $thumbName = $this->path . '/thumb_l.jpg';
                }

                // Link the thumbnail, set the objects thumbnail and return
                echo $this->path . '/' . $newThumb;
                symlink($this->path . '/' . $newThumb, $thumbName);
                $this->thumbnail = path2url($thumbName);
                return $this->thumbnail;
            }
            else return False;
        }
    }

    public function displayBox() {

        $box = new Content();
        $box->append(span($this->name));
        $wrapAttrs = array('href' => $this->url,
                           'class' => 'expBox bg_l4 mainFont');
        if ($this->get_thumbnail()) {
            $wrapAttrs['style'] = 'background-image: url(' . url_www() . $this->get_thumbnail(). ')';
        }
        else {
            $box->append(p($this->description,array('class'=>'c_l3')));
        }
        $box->wrap('a',$wrapAttrs);
        //Find a thumbnail for the experience
        return $box;
    }
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
    if ($debug) print_r($urlSubdirs);
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

function unpack_path($path) {
    return explode('/',$path);
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
    print_r($out);
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
