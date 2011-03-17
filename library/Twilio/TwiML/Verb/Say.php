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
 * @version    $Id: Say.php 4 2009-04-29 00:45:09Z leeked $
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
class Twilio_TwiML_Verb_Say extends Twilio_TwiML_Verb
{
    protected $_attributes = array(
        'voice'    => null,
        'language' => null,
        'loop'     => null,
    );

    public function setVoice($value)
    {
        $valid = array(
            'man',
            'woman',
        );

        $value = strtolower($value);
        if (in_array($value, $valid)) {
            $this->_attributes['voice'] = $value;
        } else {
            require_once 'Twilio/TwiML/Verb/Exception.php';
            throw new Twilio_TwiML_Verb_Exception('Invalid `voice` value for Say verb');
        }

        return $this;
    }

    public function setLanguage($value)
    {
        $valid = array(
            'en',
            'es',
            'fr',
            'de',
        );

        $value = strtolower($value);
        if (in_array($value, $valid)) {
            $this->_attributes['language'] = $value;
        } else {
            require_once 'Twilio/TwiML/Verb/Exception.php';
            throw new Twilio_TwiML_Verb_Exception('Invalid `language` value for Say verb');
        }

        return $this;
    }

    public function setLoop($value)
    {
        $value = (int) $value;
        if ($value >= 0) {
            $this->_attributes['loop'] = $value;
        } else {
            require_once 'Twilio/TwiML/Verb/Exception.php';
            throw new Twilio_TwiML_Verb_Exception('Invalid `loop` value for Say verb');
        }

        return $this;
    }
}
