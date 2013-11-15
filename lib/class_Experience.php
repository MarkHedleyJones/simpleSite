<?php

function unpack_name($filename) {
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
        $out['date'] = new DateTime($out['year'].'-' . $out['month'] . '-' . $out['day'] . ' 0:0:0');
        $out['dateFormatter'] = 'jS M Y';
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
        $out['date'] = new DateTime($out['year'].'-' . $out['month'] . '-1 0:0:0');
        $out['dateFormatter'] = 'M Y';
    }
    elseif ($datelen == 4) {
        $out['year'] = substr($date, 0, 4);
        $out['date'] = new DateTime($out['year'].'-1-1 0:0:0');
        $out['dateFormatter'] = 'Y';
    }
    //else trigger_error('Couldnt understand syntax of ' . $filename);

    //Get title-description from $rest
    $rest = substr($filename, $lastDigitPos);

    while (strpos($rest, '-') === 0) $rest = substr($rest, 1);
    while (strpos($rest, '_') === 0) $rest = substr($rest, 1);

    $rest = str_replace('_', ' ', $rest);
    $parts = explode('-', $rest, 2);
    $out['title'] = $parts[0];
    if (isset($parts[1])) $out['description'] = $parts[1];

    return $out;
}


class Node {
    // Represents either a file or a folder, therefore called node.

    public $date;           // DateTime object representing the node
    public $dateFormatter;  // Formatting string for showing the date
    public $year;           // Year of node
    public $month;          // Month of node
    public $day;            // Day of node
    public $title;          // Title as extracted from name
    public $description;    // Description extracted from name
    public $url;            // Where to point a browser to
    public $path;           // Full path on disk
    public $name;           // Name of folder or file

    public function __construct($location) {
        // Detect if location is url or path
        if (strpos($location, $_SERVER['DOCUMENT_ROOT']) !== FALSE) {
            $this->path = $location;
            $this->url = path2url($location);
        }
        else {
            $this->url = $location;
            $this->path = url2path($location);
        }
        $this->name = end(explode('/',$this->url));
        $tmp = unpack_name($this->name);
        foreach ($tmp as $key => $value) $this->$key = $value;
    }
}

class Event extends Node {
    // Individual things that create an experience
    // Ambiguity: Many events to one Experience
    //

    public function __construct($location) {
        parent::__construct($location);
    }
}

class Experience extends Node {
    // A collection of events
    // Ambiguity: Many events to one Experience


    public $files;
    public $thumbnail;
    public $images;

    public function __construct($location) {
        $this->thumbnail = False;
        $this->images = False;
        $this->files = Array();
        parent::__construct($location);
    }

    public static function comaprer($a, $b) {
        if ($a < $b) return True;
        else return False;
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

    public function populate_files() {
        //Supported filetypes
        $types = Array('images' => 'jpg',
                       'texts' => 'txt');
        foreach ($types AS $type => $extension) {
            $files = $this->get_files($extension,True);
            foreach ($files AS $file) {
                array_push($this->files,new Event($file));
            }
        }
    }


    public function get_files($extension, $addPath=False) {
        return array_map(function ($x) use ($addPath) {
            if ($addPath) return $this->path . '/' .  $x;
            else return $x;
        }, get_filesInDir($this->path, $extension));
    }

    public function create_imageThumbnail($image) {
        $box = new Content();
        $class = 'fancybox photo';
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
                symlink($this->path . '/' . $newThumb, $thumbName);
                $this->thumbnail = path2url($thumbName);
                return $this->thumbnail;
            }
            else return False;
        }
    }

    public function displayBox() {

        $box = new Content();
        $box->span($this->title, array('class'=>'c5', 'style'=>'display: inline-block; width: 100%'));
        $thumb = $this->get_thumbnail();
        if ($thumb == False) $thumb = url_static() . '/noPhoto.png';
        else $thumb = url_www() . $thumb;
        $box->img($thumb, '');
        $wrapAttrs = array('href' => $this->url,
                           'class' => 'expBox mainFont bg_c4');
        $box->wrap('a',$wrapAttrs);
        //Find a thumbnail for the experience
        return $box;
    }
}
