<?php
/**
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @package    Twilio
 * @subpackage Twilio
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Result.php 3 2009-04-20 13:08:03Z leeked $
 */

/**
 * @see Zend_Rest_Client_Result
 */
require_once 'Zend/Rest/Client/Result.php';

/**
 * @package    Twilio
 * @subpackage Twilio
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Twilio_Result extends Zend_Rest_Client_Result
{
    /**
     * Get Request Status
     *
     * @return boolean
     */
    public function getStatus()
    {
        $status = (int) $this->Status;

        if ($status >= 400) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get Request Error Message
     *
     * @return boolean
     */
    public function getErrorMessage()
    {
        return $this->isError() ? $this->Message : false;
    }
}
