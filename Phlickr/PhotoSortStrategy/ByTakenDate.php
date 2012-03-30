<?php

/**
 * @version $Id$
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
            GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * An object to allow the sorting of photos by their the date they were taken.
 *
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @package     Phlickr
 * @subpackage  PhotoSortStrategy
 * @since       0.2.5
 * @see         Phlickr_PhotoSorter
 */
class Phlickr_PhotoSortStrategy_ByTakenDate implements Phlickr_Framework_IPhotoSortStrategy {
    /**
     * Return the photo's date for sorting.
     *
     * @param   object Phlickr_Photo $photo
     * @return  string
     */
    function stringFromPhoto(Phlickr_Photo $photo) {
        return $photo->getTakenDate();
    }
}
