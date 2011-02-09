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
 * @subpackage Twilio_TwiML_Verb
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Pause.php 4 2009-04-29 00:45:09Z leeked $
 */

/**
 * @see Twilio_TwiML_Verb
 */
require_once 'Twilio/TwiML/Verb.php';

/**
 * @package    Twilio
 * @subpackage Twilio_TwiML_Verb
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Twilio_TwiML_Verb_Pause extends Twilio_TwiML_Verb
{
    protected $_attributes = array(
        'length' => null,
    );

    public function setLength($value)
    {
        $value = (int) $value;
        if ($value > 0) {
            $this->_attributes['length'] = $value;
        } else {
            require_once 'Twilio/TwiML/Verb/Exception.php';
            throw new Twilio_TwiML_Verb_Exception('Invalid `length` value for Pause verb');
        }

        return $this;
    }
}
