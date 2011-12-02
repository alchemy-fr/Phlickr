#!/usr/local/bin/php -q
<?php
include_once 'Phlickr/Api.php';

/**
 * I wrote this script because it's a real pain in the ass to generate the
 * authorization tokens. Hopefully this will make it a little easier.
 *
 * @version $Id$
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 */
print "This script will help you retrieve a Flickr authorization token.\n\n";

// Prevent PHP from enforcing a time limit on this script
set_time_limit(0);

// Get the user's API key and secret.
print 'API Key: ';
$api_key = trim(fgets(STDIN));
print 'API Secret: ';
$api_secret = trim(fgets(STDIN));

// Create an API object, then request a frob.
$api = new Phlickr_Api($api_key, $api_secret);
$frob = $api->requestFrob();
print "Got a frob: $frob\n";

// Find out the desired permissions.
print 'Permissions (read, write, or delete): ';
$perms = trim(fgets(STDIN));

// Build the authentication URL.
$url = $api->buildAuthUrl($perms, $frob);
print "\nOpen the following URL in your browser and and authorize:\n$url\n\n";
print "Press return when you're finished...\n";
fgets(STDIN);

// After they've granted permission, convert the frob to a token.
$token = $api->setAuthTokenFromFrob($frob);

// Print out the token.
print "Auth token: $token\n";

// Optionally, create a config file.
print 'Save these settings? (y/N): ';
$saveit = strtolower(trim(fgets(STDIN)));
if ($saveit{0} == 'y') {
    print 'Filename: ';
    $filename = trim(fgets(STDIN));
    print "Saving settings to '$filename'\n";
    $api->saveAs($filename);
    print "Use this with Phlickr_Api::createFrom() to create an object.\n";
}

exit(0);
?>
