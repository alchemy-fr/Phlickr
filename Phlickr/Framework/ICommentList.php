<?php

/**
 * @version $Id: IPhotoList.php 499 2006-01-03 22:35:52Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */

/**
 * Specifies the basic retreival functions that a PhotoList must support.
 *
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @package     Phlickr
 */
interface Phlickr_Framework_ICommentList extends Phlickr_Framework_IList {
    /**
     * Return an array of the Phlickr_Photo objects in this list.
     *
     * @return   array of Phlickr_Photo objects
     */
    function getComments();
}
