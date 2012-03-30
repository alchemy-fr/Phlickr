#!/usr/local/bin/php -q
<?php

/**
 * I wrote this script to help automate the uploading of my photos.
 *
 * After copying the photos off my camera, I rename them appending the title
 * after the original file name making 'DSC06272.JPG' into
 * 'DSC06272_mikey_driving.JPG'. The idea being that the ordering is still
 * maintained if the files are sorted by name and I can tell what a file is
 * based solely on the name.
 *
 * The downside to this method is that it caused me to do a bunch of renaming
 * once the photos were uploaded to Flickr. I wrote this to extract the title
 * from the file name and use that when uploading the photos.
 *
 * @version $Id$
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 */

class CommandlineBatchUploader
implements Phlickr_Framework_IUploadBatch
{
    protected static $PHOTO_EXTENSIONS = array('jpg', 'jpeg', 'png', 'gif');
    /**
     * @var array   files to upload
     */
    protected $_files = array();
    /**
     * Array of uploaded photo objects
     *
     * @var array of Phlickr_AuthedPhoto
     */
    protected $_photos = array();
    /**
     * Photoset title
     *
     * @var string
     */
    protected $_photosetTitle = null;
    /**
     * Regular expression for parsing out the title from the photo's filename.
     *
     * @var string
     */
    protected $_titleRegex = '';

    /**
     * Construct a upload batch.
     *
     * @param   string $directory full directory path to look for photos
     * @param   string $title_regex regular expression for parsing titles out
     *          of filenames.
     * @param   string $photoset If this is not null or empty, when the upload
     *          is completed a photoset with this name will be created and all
     *          the newly uploaded photos will be added to it.
     */
    function __construct($dir, $title_regex, $setname = null) {
        $this->_titleRegex = $title_regex;
        $this->_photosetTitle = $setname;

        // Use the Standard PHP Library's DirectoryIterator to list all the files in
        // the directory.
        foreach(new DirectoryIterator($dir) as $item) {
            // only files with the given extension...
            if ($item->isFile()) {
                $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                if (in_array($ext, self::$PHOTO_EXTENSIONS)) {
                    $this->_files[] = $item->getPathname();
                }
            }
        }
    }

    public function getFiles() {
        return $this->_files;
    }

    public function isSetWanted() {
        return (boolean) $this->_photosetTitle;
    }

    public function getSetTitle() {
        return $this->_photosetTitle;
    }

    public function getSetDescription() {
        return '';
    }

    public function getSetPrimary() {
        return null;
    }

    public function getTitleForFile($fullPath) {
        // extract the filename and extension
        $name = pathinfo($fullPath, PATHINFO_BASENAME);
        // match the pattern
        preg_match($this->_titleRegex, $name, $matches);
        
        if ($matches[1]) {
            return str_replace('_', ' ', $matches[1]);
        } else {
            // return the name minus the extension
            return substr($name, 0, - (strlen($parts['extension']) + 1));
        }
    }

    public function getDescriptionForFile($fullPath) {
        return '';
    }

    public function getTagsForFile($fullPath) {
        return array();
    }

    public function getTakenDateForFile($fullPath) {
        return null;
    }
}


// use the GetToken.php script to generate a config file.
define('API_CONFIG_FILE', dirname(__FILE__) . '/authinfo.cfg');

function getApi() {
    // set up the api connection
    $api = Phlickr_Api::createFrom(API_CONFIG_FILE);
    if (! $api->isAuthValid()) {
        die("invalid flickr logon");
    }
    return $api;
}

function getDir() {
    // depending on how this was called the first parameter might be the script
    // name. if it is, drop it.
    $argv = $_SERVER['argv'];
    if (realpath($argv[0]) == __FILE__ ) {
        array_shift($argv);
    }
    // if there was no directory passed as a parameter use the current dir.
    if (count($argv) == 0) {
        $dir = getcwd();
    } else if (is_dir($argv[0])) {
        $dir = realpath($argv[0]);
    } else {
        die("parameter was not a valid directory");
    }

    return $dir;
}

function getTags() {
    // tags?
    print 'Comma separated list of tags: ';
    // split the tags into an array, dropping commas and white space.
    $tags = preg_split("/\s*,\s*/", trim(fgets(STDIN)), -1, PREG_SPLIT_NO_EMPTY);
    if ($tags) {
        print "The photos will be tagged with '" . implode(',',$tags) . "'.\n\n";
    } else {
        print "The photos will not be tagged.\n\n";
    }
    return $tags;
}

/**
 * Findout if they want a photoset and if so, return a name based on the
 * directory.
 *
 * @param string $dir
 * @return string|null
 */
function getSetName($dir) {
    // create a photoset?
    print 'Create a photoset [y/N] ';
    if ('y' == substr(trim(fgets(STDIN)), 0, 1)) {
        $setName = basename($dir);
        print "Photos will be added to a new photoset named '$setName'.\n\n";
    } else {
        $setName = null;
        print "No photoset will be created.\n\n";
    }
    return $setName;
}




$api = getApi();

// the idea of this regular expression is that the camera file name
// starts with 3 or 4 characters, followed by 4 or 5 digits. if i've
// renamed it it would have a space, underscore or hyphen (that we'll
// ignore) and then any number of characters before ending with a period
// and then 3 character file extension.
$pattern = '/\S{3,4}\d{4,5}[ _-]?(.*)\.\S{3}/';

// the idea of this one is a two digit number, _ or - and then
// the name.jpg
#$pattern = '/\d{1,2}[ _-]?(.*)\.\S{3}/';

$dir = getDir();

// ... let the user know what we've figured out
$user = new Phlickr_AuthedUser($api);
$userName = $user->getName();
print "Uploading all the photos in '$dir' to $userName's stream\n\n";

$batcher = new CommandlineBatchUploader($dir, $pattern, getSetName($dir));
$uploader = new Phlickr_Uploader($api);
$uploader->setTags(getTags());
$uploader->uploadBatch($batcher, new Phlickr_TextUi_UploaderListener());

exit(0);

?>
