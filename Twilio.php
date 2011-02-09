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
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Twilio.php 4 2009-04-29 00:45:09Z leeked $
 */

/**
 * @see Zend_Rest_Client
 */
require_once 'Zend/Rest/Client.php';

/**
 * @see Twilio_Result
 */
require_once 'Twilio/Result.php';

/**
 * @package    Twilio
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Twilio extends Zend_Rest_Client
{
    /** Base Twilio API URI */
    const API_URI_BASE = 'https://api.twilio.com';

    /**
     * Whether or not authorization has been initialized for the current user.
     * @var bool
     */
    protected $_authInitialized = false;

    /**
     * @var Zend_Http_CookieJar
     */
    protected $_cookieJar;

    /**
     * Twilio API version to use.
     */
    protected $_apiVersion = '2008-08-01';

    /**
     * Holds the Twilio Account SID.
     */
    protected $_accountSid;

    /**
     * Holds the Twilio Account AuthToken.
     */
    protected $_authToken;

    public function __construct($accountSid, $authToken, $apiVersion = null)
    {
        iconv_set_encoding('output_encoding', 'UTF-8');
        iconv_set_encoding('input_encoding', 'UTF-8');
        iconv_set_encoding('internal_encoding', 'UTF-8');

        $this->setUri(self::API_URI_BASE);
        $this->setAccountSid($accountSid);
        $this->setAuthToken($authToken);

        if ($apiVersion !== null) {
            $this->setApiVersion($apiVersion);
        }

        $client = self::getHttpClient();
        $client->setHeaders('Accept-Charset', 'ISO-8859-1,utf-8');
    }

    public function getApiVersion()
    {
        return $this->_apiVersion;
    }

    public function setApiVersion($value)
    {
        $this->_apiVersion = $value;
        return $this;
    }

    public function getAccountSid()
    {
        return $this->_accountSid;
    }

    public function setAccountSid($value)
    {
        $this->_accountSid = $value;
        $this->_authInitialized = false;
        return $this;
    }

    public function getAuthToken()
    {
        return $this->_authToken;
    }

    public function setAuthToken($value)
    {
        $this->_authToken = $value;
        $this->_authInitialized = false;
        return $this;
    }

    public function getTwiMLInstance()
    {
        require_once 'Twilio/TwiML.php';
        $twiml = new Twilio_TwiML();
        return $twiml;
    }

    /**
     * Initialize HTTP authentication
     *
     * @return void
     */
    protected function _init()
    {
        $client = self::getHttpClient();
        $client->resetParameters();

        if (null == $this->_cookieJar) {
            $client->setCookieJar();
            $this->_cookieJar = $client->getCookieJar();
        } else {
            $client->setCookieJar($this->_cookieJar);
        }

        if (!$this->_authInitialized) {
            $client->setAuth($this->getAccountSid(), $this->getAuthToken());
            $this->_authInitialized = true;
        }
    }

    public function makeCall($caller, $called, $url, array $options = array())
    {
        // Will hold what we send to Twilio
        $data = array();

        // Valid values for $options array
        $validOptions = array(
            'Method',
            'SendDigits',
            'IfMachine',
            'Timeout',
        );

        $this->_init();
        $path = sprintf('/%s/Accounts/%s/Calls',
            $this->getApiVersion(),
            $this->getAccountSid()
        );

        // Check $options
        if (is_array($options) && !empty($options)) {
            foreach ($validOptions as $option) {
                if (isset($options[$option]) && !empty($options[$option])) {
                    $value = $options[$option];
                    switch ($option) {
                        case 'Method':
                            $value = strtoupper($value);
                            if (!in_array($value, array('POST', 'GET'))) {
                                require_once 'Twilio/Exception.php';
                                throw new Twilio_Exception('Method value must be one of POST or GET');
                            }
                            break;
                        case 'SendDigits':
                            if (!preg_match("/^(\d|#|\*)*$/i", $value)) {
                                require_once 'Twilio/Exception.php';
                                throw new Twilio_Exception('SendDigits can only contain digits, `*`, and `#`');
                            }
                            $value = urlencode($value);
                            break;
                        case 'IfMachine':
                            $value = ucfirst($value);
                            if (!in_array($value, array('Continue', 'Hangup'))) {
                                require_once 'Twilio/Exception.php';
                                throw new Twilio_Exception('IfMachine value must be one of `Continue` or `Hangup`');
                            }
                            break;
                        case 'Timeout':
                            $value = (int) $value;
                            if ($value > 999) {
                                require_once 'Twilio/Exception.php';
                                throw new Twilio_Exception('Timeout value cannot be greater than 999 seconds');
                            }
                            break;
                    }
                    $data[$option] = $value;
                }
            }
        }

        // Check Caller Number
        $len = iconv_strlen($caller, 'UTF-8');
        if ($len < 10) {
            require_once 'Twilio/Exception.php';
            throw new Twilio_Exception('Caller Number must be at least 10 characters (eg. XXX-XXX-XXXX)');
        }

        // Check Called Number
        $len = iconv_strlen($called, 'UTF-8');
        if ($len < 10) {
            require_once 'Twilio/Exception.php';
            throw new Twilio_Exception('Called Number must be at least 10 characters (eg. XXX-XXX-XXXX)');
        }

        $data['Caller'] = $caller;
        $data['Called'] = $called;
        $data['Url']    = $url;

        $response = $this->restPost($path, $data);
        return new Twilio_Result($response->getBody());
    }
}
