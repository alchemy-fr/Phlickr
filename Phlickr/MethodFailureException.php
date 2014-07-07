<?php


/**
 * Exception (optionally) thrown when an API method call fails.
 *
 * You can determine if this exception should be thrown by calling
 * Phlickr_Request's setExceptionThrownOnFailure() method.
 *
 * @package Phlickr
 * @author  Andrew Morton <drewish@katherinehouse.com>
 */
class Phlickr_MethodFailureException extends Phlickr_Exception {
}