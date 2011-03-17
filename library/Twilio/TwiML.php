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
 * @version    $Id: TwiML.php 4 2009-04-29 00:45:09Z leeked $
 */

/**
 * @see Twilio_TwiML_Verb
 */
require_once 'Twilio/TwiML/Verb.php';

/**
 * @package    Twilio
 * @subpackage Twilio
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Twilio_TwiML
{
    /**
     * Whether XML output should be formatted
     *
     * @var bool
     */
    protected $_formatOutput = false;

    /**
     * API Version to use (optional)
     *
     * @var string
     */
    protected $_apiVersion;

    /**
     * Holds the TwiML elements to parse later
     *
     * @var array
     */
    private $_twiML = array();

    public function __construct($apiVersion = null, $formatOutput = true)
    {
        if ($apiVersion !== null) {
            $this->setApiVersion($apiVersion);
        }

        $this->setFormatOutput($formatOutput);
    }

    /**
     * Creates a verb instance
     *
     * @param string $name      Verb name
     * @param string $value     Verb value (optional)
     * @param array $attributes Verb attributes (optional)
     * @return Twilio_TwiML_Verb
     */
    static public function createVerb($name, $value = null, array $attributes = array())
    {
        $name = ucfirst($name);
        require_once 'Twilio/TwiML/Verb/' . $name . '.php';
        $class = 'Twilio_TwiML_Verb_' .  $name;
        $verb  = new $class;
        $verb->setValue($value);
        $verb->setAttribs($attributes);

        return $verb;
    }

    /**
     * Adds a verb to the stack
     *
     * @param string|Twilio_TwiML_Verb $verb
     * @param string $value     Verb value (optional)
     * @param array $attributes Verb attributes (optional)
     * @return Twilio_TwiML
     */
    public function addVerb($verb, $value = null, array $attributes = array())
    {
        if (is_string($verb)) {
            $verb = self::createVerb($verb);
        } elseif (!($verb instanceof Twilio_TwiML_Verb)) {
            require_once 'Twilio/TwiML/Verb/Exception.php';
            throw new Twilio_TwiML_Verb_Exception('addVerb() must be passed either a string or a Verb object');
        }

        $this->_twiML[] = $verb;
        return $this;
    }

    /**
     * Removes a verb from the stack
     *
     * @param int $index
     * @return Twilio_TwiML
     */
    public function removeVerb($index)
    {
        $index = (int) $index;

        if (isset($this->_twiML[$index])) {
            unset($this->_twiML[$index]);
        } else {
            require_once 'Twilio/TwiML/Verb/Exception.php';
            throw new Twilio_TwiML_Verb_Exception('Index `' . $index . '` does not exist.');
        }

        return $this;
    }

    /**
     * Returns the last verb added to the stack
     */
    public function getLastVerb()
    {
        return end($this->_twiML);
    }

    /**
     * Primary Verb: Say
     *
     * @see http://www.twilio.com/docs/api_reference/TwiML/say
     * @static
     */
    static public function say($value, array $attributes = array())
    {
        return self::createVerb('Say', $value, $attributes);
    }

    /**
     * Primary Verb: Play
     *
     * @see http://www.twilio.com/docs/api_reference/TwiML/play
     * @param int|array $loop
     * @static
     */
    static public function play($loop = null)
    {
        $attributes = array();
        if (is_array($loop)) {
            $attributes = $loop;
        } else {
            $attributes = array(
                'loop' => $loop
            );
        }

        return self::createVerb('Play', null, $attributes);
    }

    /**
     * Primary Verb: Gather
     *
     * @see http://www.twilio.com/docs/api_reference/TwiML/gather
     * @static
     */
    static public function gather($value, array $attributes = array())
    {
        return self::createVerb('Gather', $value, $attributes);
    }

    /**
     * Primary Verb: Record
     *
     * @see http://www.twilio.com/docs/api_reference/TwiML/record
     * @static
     */
    static public function record()
    {
        require_once 'Twilio/TwiML/Verb/Exception.php';
        throw new Twilio_TwiML_Verb_Exception('Verb `Record` has not yet been implemented.');
    }

    /**
     * Primary Verb: Dial
     *
     * @see http://www.twilio.com/docs/api_reference/TwiML/dial
     * @static
     */
    static public function dial()
    {
        require_once 'Twilio/TwiML/Verb/Exception.php';
        throw new Twilio_TwiML_Verb_Exception('Verb `Dial` has not yet been implemented.');
    }

    /**
     * Secondary Verb: Redirect
     *
     * @see http://www.twilio.com/docs/api_reference/TwiML/redirect
     * @static
     */
    static public function redirect()
    {
        require_once 'Twilio/TwiML/Verb/Exception.php';
        throw new Twilio_TwiML_Verb_Exception('Verb `Redirect` has not yet been implemented.');
    }

    /**
     * Secondary Verb: Pause
     *
     * @see http://www.twilio.com/docs/api_reference/TwiML/pause
     * @param int|array $length
     * @static
     */
    static public function pause($length = null)
    {
        $attributes = array();
        if (is_array($length)) {
            $attributes = $length;
        } else {
            $attributes = array(
                'length' => $length
            );
        }

        return self::createVerb('Pause', null, $attributes);
    }

    /**
     * Secondary Verb: Hangup
     *
     * @see http://www.twilio.com/docs/api_reference/TwiML/hangup
     * @static
     */
    static public function hangup()
    {
        return self::createVerb('Hangup', null, array());
    }

    /**
     * Sets whether XML output should be formatted
     *
     * @param bool $value  format XML output
     */
    public function setFormatOutput($value)
    {
        $this->_formatOutput = (bool) $value;
    }

    /**
     * Returns true if XML output should be formatted
     *
     * @return bool
     */
    public function getFormatOutput()
    {
        return $this->_formatOutput;
    }

    /**
     * Returns any API Version that might be set
     *
     * @return string
     */
    public function getApiVersion()
    {
        return $this->_apiVersion;
    }

    /**
     * Sets whether we should specify an API Version
     *
     * @param string $value
     */
    public function setApiVersion($value)
    {
        $this->_apiVersion = $value;
        return $this;
    }

    /**
     * Returns a DOMDocument containing the TwiML
     *
     * @return DOMDocument
     */
    public function getDom()
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        // Start <Response>
        $response = $dom->createElement('Response');
        $dom->appendChild($response);

        // Set API Version if need be (optional)
        if ($this->getApiVersion() != null) {

            $version = $dom->createAttribute('version');
            $response->appendChild($version);

            $versionValue = $dom->createTextNode($apiVersion);
            $version->appendChild($versionValue);

        }

        // Add each Verb Element (recursively for nested elements)
        $dom = $this->_twiMLtoDOM($dom, $response, $this->_twiML);
        return $dom;
    }

    /**
     * This function recursively creates DOMElements from
     * the TwiML classes.
     *
     * @param DOMDocument $dom   DOMDocument to work on
     * @param DOMElement $parent Parent DOMElement to add to
     * @param array $twiML       TwiML to add
     * return DOMDocument
     */
    private function _twiMLtoDOM(DOMDocument $dom, DOMElement $parent, array $twiML)
    {
        foreach ($twiML as $id => $element) {

            if ($element instanceof Twilio_TwiML_Verb) {

                $newElementName  = $element->getVerb();
                $newElementValue = $element->getValue();

                if (!empty($newElementValue)) {
                    $newElement = $dom->createElement($newElementName, $newElementValue);
                } else {
                    $newElement = $dom->createElement($newElementName);
                }

                $parent->appendChild($newElement);

                // Add any Attributes
                $attributes = $element->getAttribs();
                if (is_array($attributes) && !empty($attributes)) {

                    foreach ($attributes as $name => $value) {

                        if ($value !== null) {

                            $attribute = $dom->createAttribute($name);
                            $newElement->appendChild($attribute);

                            $attributeValue = $dom->createTextNode($value);
                            $attribute->appendChild($attributeValue);

                        }

                    }

                }

                // Add any nested Verbs
                $nestedVerbs = $element->getNestedTwiML();
                if (!empty($nestedVerbs)) {
                    $dom = $this->_twiMLtoDOM($dom, $newElement, $nestedVerbs);
                }

            }

        }

        return $dom;
    }

    /**
     * Render
     *
     * @return string
     */
    public function render()
    {
        $dom = $this->getDom();
        return $dom->saveXML();
    }

    /**
     * Renders the registered container as XML (TwiML)
     *
     * @return string
     */
    public function toString()
    {
        return $this->render();
    }

    /**
     * Tostring
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
