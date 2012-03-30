#!/usr/local/bin/php -q
<?php

/**
 * Find photos by tags and then set their taken date to a given month.
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

print "This script lets you select photos by tag and then set the taken date\n";
print "to a month-year date.\n\n";

// set up the api connection
$api = Phlickr_Api::createFrom(API_CONFIG_FILE);
$api->setCacheFilename(CACHE_FILE);
if (!$api->isAuthValid()) die("invalid flickr logon");

// get a list of tags
print 'Enter a comma separated list of tags: ';
$tags = trim(fgets(STDIN));

// create a request to search for your photos with the tags.
$request = $api->createRequest(
    'flickr.photos.search',
    array('tags' => $tags, 'tag_mode' => 'all', 'user_id' => $api->getUserId())
);

// use the photo list to parse the search results
print "Searching for matching photos tagged with '$tags'...\n";
$pl = new Phlickr_PhotoList($request, Phlickr_PhotoList::PER_PAGE_MAX);
print "Found {$pl->getCount()} photos.\n";

print 'Year: ';
$year = (integer) trim(fgets(STDIN));
print 'Month: ';
$month = (integer) trim(fgets(STDIN));

print "The photo's taken date will be set to $year-$month.\n";
print "Press return to continue or CTRL+C to quit.\n";
fgets(STDIN);

$pi = new Phlickr_PhotoListIterator($pl);
foreach ($pi->getPhotos() as $p) {
    print "Changing date on '{$p->getTitle()}'...\n";
    $p->setTaken($year .'-'. $month, 4);
}

exit(0);
