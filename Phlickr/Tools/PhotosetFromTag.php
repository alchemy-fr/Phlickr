#!/usr/local/bin/php -q
<?php

/**
 * Create a photoset from all your photos with given tags.
 *
 * Use the GetToken.php script to generate a settings file with an API key,
 * secret, and token. Edit the API_CONFIG_FILE constant to point to it.
 *
 * @version $Id$
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 */

// use the GetToken.php script to generate a config file.
define('API_CONFIG_FILE',  dirname(__FILE__) . './authinfo.cfg');
// the cache file isn't required but if you share it's nice.
define('CACHE_FILE', dirname(__FILE__) . '/cache.tmp');

// set up the api connection
$api = Phlickr_Api::createFrom(API_CONFIG_FILE);
if (! $api->isAuthValid()) {
    die("invalid flickr logon");
}
$api->setCacheFilename(CACHE_FILE);

// get a list of tags
printf('Comma separated list of tags: ');
$tags = trim(fgets(STDIN));

// create a request to search for photos tagged with person and happy
// from all users.
$request = $api->createRequest(
    'flickr.photos.search',
    array(
        'tags' => $tags,
        'tag_mode' => 'all',
        'user_id' => $api->getUserId()
    )
);

// use the photo list and photo list iterator to display the titles and urls
// of each of the photos.
printf("Searching for matching photos tagged with '%s'...\n", $tags);
$pl = new Phlickr_PhotoList($request, Phlickr_PhotoList::PER_PAGE_MAX);

// create a sorter that will sort by color
$sorter = new Phlickr_PhotoSorter(new Phlickr_PhotoSortStrategy_ById());
// use a photolist iterator so that all the pages are sorted.
$photos = $sorter->sort(new Phlickr_PhotoListIterator($pl));
$photo_ids = Phlickr_PhotoSorter::idsFromPhotos($photos);

if (count($photo_ids) == 0) {
    printf("No photos were found.\n", $tags);
} else {
    printf("Found %d photos...\n", count($photo_ids));

    $apsl = new Phlickr_AuthedPhotosetList($api);
    $aps = $apsl->create($tags, 'This photoset was created from the tag(s) '
        . $tags, $photo_ids[0]);
    $aps->editPhotos($photo_ids[0], $photo_ids);

    printf("Created a photoset named '%s'. You can view it at:\n%s\n",
        $tags, $aps->buildUrl());
}

exit(0);
?>

