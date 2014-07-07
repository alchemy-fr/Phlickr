<?php

/**
 * Phlickr makes use of PHP5 exceptions to simplify the detection of and
 * differentiation programming and connection errors.
 *
 * @version $Id: Exception.php 500 2006-01-03 23:29:08Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php GNU Lesser General
 *          Public License, Version 2.1
 * @package Phlickr
 * @todo    Split this into separate files.
 */

/**
 * Exception base class thrown when there is no more specific exception.
 * Callers should use this as their final catch when calling various Phlickr
 * functions.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 */
class Phlickr_Exception extends Exception {
}
