<?php
/*
 * Load Config File Settings
 */
Configure::load('PaypalWPP.paypal');
 
/*
 * Paypal WPP Component
 * 
 * @author Chris Pierce <cpierce@csdurant.com>
 * @author Eric McBride <ericmcbridedevleoper@gmail.com>
 */
App::uses('Component', 'Controller');
App::uses('HttpSocket', 'Network/Http');

class PaypalWPPComponent extends Component {
	
	/*
	 * Web Payments Pro Hash
	 * 
	 * @throws BadRequestException
	 * @param string $method, string $nvp
	 * @return mixed[mixed]
	 * TODO: SIGN UP FOR PAYPAL
	 */
	public function wpp_hash($method = null, $nvp = null) {
		$HttpSocket = new HttpSocket();

		$required_nvp = 'METHOD='.$method;
		$required_nvp .= '&VERSION='.Configure::read('Paypal.version');
		$required_nvp .= '&USER='.Configure::read('Paypal.username');
		$required_nvp .= '&PWD='.Configure::read('Paypal.password');
		$required_nvp .= '&SIGNATURE='.Configure::read('Paypal.signature');
		debug($required_nvp);
		die();
		$http_responder = $HttpSocket->post(Configure::read('Paypal.endpoint'), $required_nvp.$nvp);
		if (!$http_responder) {
			throw new BadRequestException($method.'failed: '.$http_responder['reasonPhrase']);
		}
		
		$responder = explode('&', $http_responder);
		$parsed_response = array();
		
		foreach($responder as $response) {
			$response_array = explode('=', $response);
			if (count($response_array) >= 1)
				$parsed_response[$response_array[0]] = urldecode($response_array[1]);
		}
		
		if ((count($parsed_response) < 1) || !array_key_exists('ACK', $parsed_response))
			throw new BadRequestException('Invalid HTTP Response for POST request ('.$required_nvp.$nvp.') to '.Configure::read('Paypal.endpoint'));
		
		return $parsed_response;
	}

} 
