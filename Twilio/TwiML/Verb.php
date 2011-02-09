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
 * @subpackage Twilio_TwiML
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Verb.php 4 2009-04-29 00:45:09Z leeked $
 */

/**
 * @package    Twilio
 * @subpackage Twilio_TwiML
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Twilio_TwiML_Verb
{
    /**
     * Verb name
     *
     * @var string
     */
    protected $_verb;

    /**
     * Verb value (sometimes optional)
     *
     * @var string
     */
    protected $_value;

    /**
     * Verb attributes (sometimes optional)
     *
     * @var null|array
     */
    protected $_attributes;

    /**
     * Verbs that can be nested in this Verb
     *
     * @var null|array
     */
    protected $_nestableVerbs;

    /**
     * Nested TwiML objects
     *
     * @var array
     */
    protected $_nestedTwiML = array();

    public function __construct($value = null)
    {
        if ($value !== null) {
            $this->setValue($value);
        }

        if ($this->_verb === null) {
            $name        = get_class($this);
            $parts       = explode('_', $name);
            $this->_verb = end($parts);
        }
    }

    /**
     * Returns any nested Verbs
     *
     * return array
     */
    public function getNestedTwiML()
    {
        if (is_array($this->_nestedTwiML)) {
            return $this->_nestedTwiML;
        }

        return array();
    }

    /**
     * Adds a verb to the nest
     *
     * @param string|Twilio_TwiML_Verb $verb
     * @param string $value     Verb value (optional)
     * @param array $attributes Verb attributes (optional)
     * @return Twilio_TwiML
     */
    public function addVerb($verb, $value = null, array $attributes = array())
    {
        if (is_string($verb)) {
            $verb = Twilio_TwiML::createVerb($verb);
        } elseif (!($verb instanceof Twilio_TwiML_Verb)) {
            require_once 'Twilio/TwiML/Verb/Exception.php';
            throw new Twilio_TwiML_Verb_Exception('addVerb() must be passed either a string or a Verb object');
        }

        $this->_nestedTwiML[] = $verb;
        return $this;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    public function getVerb()
    {
        return $this->_verb;
    }

    public function setAttrib($name, $value)
    {
        if ($value !== null) {

            $method = 'set' . ucfirst($name);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }

        }

        return $this;
    }

    public function setAttribs(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->setAttrib($name, $value);
        }

        return $this;
    }

    public function getAttribs()
    {
        return $this->_attributes;
    }

    public function getAttrib($name)
    {
        return $this->_attributes[$name];
    }

    public function getName()
    {
        return $this->_verb;
    }
}
