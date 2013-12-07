<?php

function unpack_name($filename) {
    $out = Array();
    $lastDigitPos = 0;
    $strlen = strlen($filename);
    for ($i = 0; $i < $strlen; $i++) {
        if (is_numeric($filename[$i])) $lastDigitPos = $i + 1;
        elseif (ctype_alpha($filename[$i])) break;
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
        if (strpos($location, BASE_PATH) !== FALSE) {
            $this->path = $location;
            $this->url = clean_path(path2url($location));
        }
        else {
            $this->url = $location;
            $this->path = clean_path(url2path($location));
        }

        if (substr($this->url, -1) == '/') $this->name = end(explode('/',substr($this->url,0,-1)));
        else $this->name = end(explode('/', $this->url));

        $tmp = unpack_name($this->name);
        foreach ($tmp as $key => $value) $this->$key = $value;

        if ($this->date == False) {
            $this->date = new DateTime(date('c', filectime($this->path)));
            $this->dateFormatter = 'jS M Y';
            $this->year = $this->date->format('Y');
            $this->month = $this->date->format('M');
            $this->day = $this->date->format('d');
        }
    }
}

class File extends Node {

    public $extension;

    public function __construct($location) {
        $this->extension = end(explode('.', $location));
        parent::__construct($location);
    }

    public function render($accomodate_html=False) {
        switch (strtolower($this->extension)) {
            case 'html':
                return div(file_get_contents($this->path),Array('class'=>'htmlBox'));
                break;

            case 'jpg':
                if ($this->is_cached() == False) {
                    $this->cache_image();
                }
                //return $this->as_thumbnail(($accomodate_html ? 'r' : False));
                return $this->as_thumbnail();
        }
    }

    public function name_thumbnail() {
        return clean_path(str_replace('.'.$this->extension, '_thumb.'.$this->extension, $this->url));
    }

    public function as_thumbnail($float=False) {
        $c = new Content();
        $attrs = array('href'=>$this->url,
                'class' => 'fancybox photo',
                'rel' => 'gallery',
                'title' => $this->name);

        if ($float != False) {
            if ($float == 'r') {
                $attrs['class'] .= ' fr';
                $attrs['style'] = 'clear: right';
            }
            else {
                $attrs['class'] .= ' fl';
                $attrs['style'] = 'clear: left';
            }
        }

        $c->append(img( $this->name_thumbnail(),""));
        $c->wrap('a',$attrs);

        return $c;
    }

    public function get_thumbnail() {
        if ($this->is_cached() == False) $this->cache_image();
        return $this->name_thumbnail();
    }


    public function is_cached($debug=False) {
        $path = clean_path($_SERVER['DOCUMENT_ROOT'] . $this->url);
        $cached = file_exists($path);
        if ($debug){
            print '<br><br>Checking for cached image in "' . $path . '"';
            if ($cached === True) print '<br>Image cached';
            else print '<br>Image not cached';
        }
        return $cached;
    }

    public function cache_image($debug=False) {
        $path_from = clean_path(BASE_PATH . $this->url);
        $path_to = clean_path($_SERVER['DOCUMENT_ROOT'] . $this->url);
        $command = "mkdir -p " . substr($path_to, 0, strrpos($path_to, '/', -1) + 1);
        if ($debug) {
            print '<br><br>Caching image from ' . $path_from . ' to ' . $path_to;
            print '<br>Executing command: ' . $command;
        }
        exec($command);

        // Create web rescaled image
        $command = "convert " . $path_from . " -resize '1024x768>' " . $path_to;
        if ($debug) print '<br>Executing command: ' . $command;
        exec($command);

        // Create thumbnail
        $command = "convert " . $path_from . " -resize '256' " . str_replace('.'.$this->extension, '_thumb.'.$this->extension, $path_to);
        if ($debug) print '<br>Executing command: ' . $command;
        exec($command);

    }
}


class Experience extends Node {
    // A collection of events
    // Ambiguity: Many events to one Experience


    public $files;
    public $thumbnail;
    public $contains_html;

    public function __construct($location) {
        $this->thumbnail = False;
        $this->files = Array();
        $this->contains_html = False;
        parent::__construct($location);
    }

    public function render() {
        $c = new Content();
        $this->populate_files();
        $imgs = 0;
        // Render html files if any
        if ($this->contains_html) {
            foreach ($this->files AS $file) {
                if (strtolower($file->extension) == 'html') {
                    $c->append($file->render($this->contains_html));
                }
            }
        }
        foreach ($this->files AS $file) {
            if (strtolower($file->extension) == 'jpg') {
                if ($this->contains_html && $imgs < 3) {
                    $c->prepend($file->render($this->contains_html));
                    ++$imgs;
                }
                else {
                    $c->append($file->render($this->contains_html));
                }
            }
        }
        return $c;
    }

    public function populate_files($type=False) {
        //Supported filetypes
        $types = Array('images' => 'jpg',
                       'texts' => 'txt',
                       'html' => 'html');

        //Filter down to passed type
        if ($type != False) $types = Array($type => $types[$type]);

        foreach ($types AS $type => $extension) {
            $files = $this->get_files($extension,True);
            foreach ($files AS $file) {
                if ($type == 'html') $this->contains_html = True;
                array_push($this->files,new File($file));
            }
        }
    }

    public function get_files($extension, $addPath=False) {
        return array_map(function ($x) use ($addPath) {
            if ($addPath) return $this->path .  $x;
            else return $x;
        }, get_filesInDir($this->path, $extension));
    }

    public function displayBox() {

        $box = new Content();
        $box->span($this->title, array('class'=>'c5', 'style'=>'display: inline-block; width: 100%'));
        $thumbnail = $this->get_thumbnail();
        if ($thumbnail == False) $thumb = url_static() . '/noPhoto.png';
        else $thumb = $thumbnail->get_thumbnail();
        $box->img($thumb, '');
        $wrapAttrs = array('href' => clean_path($this->url),
                           'class' => 'expBox mainFont bg_c4');
        $box->wrap('a',$wrapAttrs);
        //Find a thumbnail for the experience
        return $box;
    }


    public function get_thumbnail() {
        $images = $this->get_files('jpg');
        $maxChars = 0;
        $maxImg = '';
        foreach ($images as $image) {
            $strlen = strlen($image);
            $count = 0;
            for ($i = 0; $i < $strlen; $i++) {
                if (ctype_alpha($image[$i])) $count++;
            }
            if ($count > $maxChars) {
                $maxChars  = $count;
                $maxImg = $image;
            }
        }
        if ($maxImg != '') {
            $name = explode('.', $maxImg);
            $imgName = $name[0] . '.' . $name[1];
            return new File($this->path . $imgName);

        }
        else return False;
    }
}

class ExperienceList {

    public $experiences;

    public function __construct() {

        $subdirs = array_map(function ($x) {return str_replace('/', '', $x);},get_subdirs('/'));

        $this->experiences = Array();
        foreach ($subdirs AS $type) $this->experiences[$type] = Array();

        foreach ($subdirs as $type) {
            $path = '/' . $type . '/';
            $experiences = get_subdirs($path);
            $index = array_map(function ($x) use ($path) {return str_replace($path, '', $x);}, $experiences);
            foreach ($index as $name) {
                array_push($this->experiences[$type], new Experience($path . $name));
            }
        }
    }

    public function get_experience($type, $name) {
        foreach ($this->experiences[$type] as $experience) {
            if ($experience->name == $name) return $experience;
        }
        return False;
    }

    public function types() {
        return array_keys($this->experiences);
    }

    public function get_all() {
        $all = Array();
        foreach ($this->types() AS $expType) $all = array_merge($all, $this->experiences[$expType]);
        return $all;
    }

    public function mostRecent($num) {
        return first($num, ExperienceList::ordered_byDate($this->get_all()));
    }

    public static function ordered_byDate($array) {
        return ExperienceList::sorted($array);
    }


    public static function comaprer($a, $b) {
        if ($a->date < $b->date) return True;
        else return False;
    }


    public static function sorted($input) {
        usort($input, 'ExperienceList::comaprer');
        return $input;
    }

}