#!/usr/local/bin/php -q
<?php

/**
 * @version $Id$
 * @author Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *      GNU Lesser General Public License, Version 2.1
 *
 * This script grabs a random Favorite photo from a Flickr account and saves it
 * as the Windows desktop background image.
 *
 * Requirements:
 *  - PHP5 and the PECL php_ffi.dll extension (http://www.php.net/downloads.php)
 *  - Phlickr (http://sourceforge.net/project/showfiles.php?group_id=129880)
 *  - ImageMagick (http://imagemagick.org/script/binary-releases.php#windows)
 *
 * Installation:
 *  - Make sure that the required software listed above is installed.
 *      - You're on your own with PHP and ImageMagick, they're both easy.
 *      - For the FFI extension:
 *         - Download it. It comes bundled with the "Collection of PECL modules
 *           for PHP". Make sure the version number matches PHP's.
 *         - Extract the contents into the PHP extensions directory, probably
 *           something like C:\PHP5\ext.
 *         - Make sure you the php.ini file sets extension_dir directive:
 *           extension_dir = "C:\PHP5\ext\"
 *  - Adjust the constants definded below.
 *  - Run this script to make sure everything works: 'php favoriteDesktop.php'
 *  - Set the desktop settings:
 *      - Right-click on the Windows desktop and select "Properties".
 *      - Select the "Desktop" tab.
 *      - Select "Center" in the "Position" combobox.
 *      - Choose a nice dark color.
 *      - Click Ok.
 *  - Create a scheduled task to automate the downloading:
 *      - Start > Settings > Control Panel > Scheduled Tasks
 *      - Click "Add Scheduled Task"
 *      - Read the introduction then click "Next"
 *      - Click "Browse", locate and select "php.exe", then click "Ok"
 *      - Choose when you'd like the script run then click "Next".
 *      - Set the scheduling options then click "Next".
 *      - Enter a username/password if you want the script to run when you're
 *          not logged on. Click "Next".
 *      - Click "Finish" and the advanced properties setting should appear.
 *      - In the properties form select the "Run" field. You'll need to add
 *          the full path to the script to the end of PHP's path. I.e.:
 *          "C:\php\php.exe" "C:\WINDOWS\Temp\favoriteDesktop.php"
 *          Make sure that each part is double quoted.
 *      - If you didn't set the password, check the "Run only if logged on"
 *          option.
 *      - Click "Ok".
 *      - Right-click on the newly created task and select the "Run" option.
 *          The script should be run and your desktop should be changed.
 */


define('FLICKR_EMAIL', 'drewish@katherinehouse.com');
define('DESKTOP_SIZE', '1024x768');
define('WALLPAPER_FILE', 'C:\WINDOWS\Temp\desktop.bmp');
define('CACHE_FILE', 'C:\WINDOWS\Temp\phlickr.cache');

// YOU SHOULDN'T NEED TO CHANGE ANYTHING BELOW HERE

define('FLICKR_API_KEY', '0b3ee85b948bf9b3382bdc627bacf5bc');
define('FLICKR_API_SECRET', 'b00205f7d632d0ea');

try {
    $imgTempFile = tempnam('/tmp', 'jpg');

    print 'Selecting a random favorite photo (' . FLICKR_EMAIL . ") ...\n";
    $photo = getRandomFavoritePhoto(FLICKR_EMAIL);

    print "Saving the photo ($imgTempFile) ...\n";
    $photo->saveAs($imgTempFile, Phlickr_Photo::SIZE_ORIGINAL);

    print 'Converting JPEG to BMP file (' . WALLPAPER_FILE. ") ...\n";
    convertImageToBmp($imgTempFile, WALLPAPER_FILE, DESKTOP_SIZE);

    print "Setting the the wallpaper ...\n";
    setWindowsDesktop(WALLPAPER_FILE);

    print "Deleting temporary download file ...\n";
    unlink($imgTempFile);
} catch (Exception $ex) {
    fprintf(STDERR, "\nThere was a problem: " . $ex->getMessage());
    printf("\nPress return to exit...\n");
    fgets(STDIN);
}
exit(0);


/**
 * Get a random favorite photo from a Flickr user.
 *
 * @param   string  $userEmail Email address of a Flickr user
 * @return  object Phlickr_Photo
 */
function getRandomFavoritePhoto($userEmail) {
    $api = new Phlickr_Api(FLICKR_API_KEY, FLICKR_API_SECRET);
    // load a saved cache file if it exists, set the expiration limit to a week.
    $api->setCache(Phlickr_Cache::createFrom(CACHE_FILE, 60*60*24*7));

    // select a random favorite photo
    $user = Phlickr_User::findByEmail($api, $userEmail);
    $favlist = $user->getFavoritePhotoList();
    $photo = $favlist->getRandomPhoto();

    assert(!is_null($photo));

    // serialize and save the cache file
    $api->getCache()->saveAs(CACHE_FILE);

    return $photo;
}

/**
 * Use ImageMagick to convert a JPEG, PNG or GIF to a Windows BMP.
 *
 * Note: this function requires that ImageMagick's 'convert' program be
 * located in the system path.
 *
 * @param   string  $inputFilePath Full path to the input file (JPG, GIF or PNG).
 * @param   string  $bmpFilePath Full path to the BMP output file.
 * @param   string  $destopSize Dimensions of the desktop in the form "1024x768".
 * @return  void
 */
function convertImageToBmp($inputFilePath, $bmpFilePath, $destopSize) {
    assert(file_exists($inputFilePath));
    $cmd = sprintf('convert �type TrueColor -density 97x97 -resize %s "%s" bmp:"%s"',
        $destopSize, $inputFilePath, $bmpFilePath);
    exec($cmd);
    assert(file_exists($bmpFilePath));
}

/**
 * Set the Windows Desktop Wallpaper to a given file.
 *
 * This function uses the FFI module, a really cool package that lets you load
 * native system libraries and call them from PHP. Obviously, it needs to be
 * installed.
 *
 * @param   string  $bmpFilePath Full path to the BMP file.
 * @return  void
*/
function setWindowsDesktop($bmpFilePath) {
    define('SPI_SETDESKWALLPAPER', 0x14);
    define('SPIF_UPDATEINIFILE', 0x1);
    define('SPIF_SENDWININICHANGE', 0x2);

    assert(file_exists($bmpFilePath));

    // Load the extension
    if (!dl("php_ffi.dll")) {
        throw new Exception('Cound not load the FFI extension.');
    }

    // declare the Win32 API function used to change desktop settings.
    $ffi = new FFI(
<<<IDL
[lib='User32.dll']
int SystemParametersInfoA(int uAction, int uParam, char *lpvParam, int fuWinIni);
[lib='Kernel32.dll']
int GetLastError();
IDL
    );
    // call the Windows API to update the desktop background.
    $ret = $ffi->SystemParametersInfoA(SPI_SETDESKWALLPAPER, 0, $bmpFilePath,
        SPIF_UPDATEINIFILE || SPIF_SENDWININICHANGE);

    if ($ret == 0) {
        $error = $ffi->GetLastError();
        throw new Exception("The call to the Windows API failed (error $error).");
    }
}

?>
