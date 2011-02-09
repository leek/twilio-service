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
 * @version    $Id: Say.php 3 2009-04-20 13:08:03Z leeked $
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
class Twilio_TwiML_Verb_Gather extends Twilio_TwiML_Verb
{
    protected $_attributes = array(
        'action'      => null,
        'method'      => null,
        'timeout'     => null,
        'finishOnKey' => null,
        'numDigits'   => null,
    );

    protected $_nestableVerbs = array(
        'Play',
        'Say',
        'Pause',
    );

    /**
     * Action
     *
     * @return Twilio_TwiML_Verb
     */
    public function setAction($value)
    {
        $this->_attributes['action'] = $value;

        return $this;
    }

    /**
     * Method
     *
     * @return Twilio_TwiML_Verb
     */
    public function setMethod($value)
    {
        $valid = array(
            'GET',
            'POST',
        );

        $value = strtoupper($value);
        if (in_array($value, $valid)) {
            $this->_attributes['method'] = $value;
        } else {
            require_once 'Twilio/TwiML/Verb/Exception.php';
            throw new Twilio_TwiML_Verb_Exception('Invalid `method` value for Gather verb');
        }

        return $this;
    }

    /**
     * Timeout
     *
     * @return Twilio_TwiML_Verb
     */
    public function setTimeout($value)
    {
        $value = (int) $value;
        if ($value > 0) {
            $this->_attributes['timeout'] = $value;
        } else {
            require_once 'Twilio/TwiML/Verb/Exception.php';
            throw new Twilio_TwiML_Verb_Exception('Invalid `timeout` value for Gather verb');
        }

        return $this;
    }

    /**
     * FinishOnKey
     *
     * @return Twilio_TwiML_Verb
     */
    public function setFinishonkey($value)
    {
        $this->_attributes['finishOnKey'] = $value;

        return $this;
    }

    /**
     * NumDigits
     *
     * @return Twilio_TwiML_Verb
     */
    public function setNumdigits($value)
    {
        $value = (int) $value;
        if ($value > 0) {
            $this->_attributes['numDigits'] = $value;
        } else {
            require_once 'Twilio/TwiML/Verb/Exception.php';
            throw new Twilio_TwiML_Verb_Exception('Invalid `numDigits` value for Gather verb');
        }

        return $this;
    }
}
