# Twilio Service ![Project status](http://stillmaintained.com/leek/twilio-zf-service.png)
Twilio API client written in PHP utilizing Zend Framework's Zend_Rest

## About

This is a simple to use Twilio client written in PHP using the Zend_Rest_Client class from the Zend Framework.

This project also includes a fully featured class for generating TwiML (Twilio's XML response markup).

## TwiML Examples

Here is an example TwiML snippet from the Twilio docs:

    <?xml version="1.0" encoding="UTF-8"?>  
    <Response>  
        <Gather action="/process_gather.php" method="GET">  
            <Say>  
                Please enter your account number,   
                followed by the pound sign  
            </Say>  
        </Gather>  
        <Say>We didn't receive any input. Goodbye!</Say>  
    </Response>
    
To do this with the TwiML component of this library, try the following:

    $twiml = new Twilio_TwiML();
    $twiml->addVerb(
        $twiml->gather(null, array('action' => '/process_gather.php', 'method' => 'GET'))
              ->addVerb($twiml->say('Please enter your account number, followed by the pound sign.'))
    );
    $twiml->addVerb($twiml->say('We didn\'t receive any input. Goodbye!'));
    echo $twiml->render();

Or...

    $twiml = Twilio::getTwiMLInstance()
    $twiml->addVerb(
        $twiml->gather(null, array('action' => '/process_gather.php', 'method' => 'GET'))
              ->addVerb($twiml->say('Please enter your account number, followed by the pound sign.'))
    );
    $twiml->addVerb('Say', 'We didn\'t receive any input. Goodbye!'); 
    echo $twiml->render();