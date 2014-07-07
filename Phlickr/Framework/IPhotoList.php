<?php

/**
 * @version $Id: IPhotoList.php 532 2007-02-08 06:55:29Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * This class implements IList.
 */
require_once 'Phlickr/Framework/IList.php';
/**
 * One or more methods returns Phlickr_Photo and Phlickr_AuthedPhoto objects.
 */
require_once 'Phlickr/AuthedPhoto.php';

/**
 * Specifies the basic retreival functions that a PhotoList must support.
 *
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @package     Phlickr
 */
interface Phlickr_Framework_IPhotoList extends Phlickr_Framework_IList {
    /**
     * Return an array of the Phlickr_Photo objects in this list.
     *
     * @return  array object Phlickr_AuthedPhoto or Phlickr_Photo depending
     *          on the owner.
     */
    function getPhotos();
}
