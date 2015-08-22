PayPal WebPaymentsPro (WPP) Component for CakePHP 2.x
===================================

CakePHP 2.x Component for interfacing with Paypal WPP

Usage:
===================================
Load plugin in your APP and enable it by using the following bootstrap.php config:

```
	CakePlugin::load('PaypalWPP');
	
```

Configure your account by opening the Config/paypal.php file as follows

```
	$config = array(
		'paypal' => array(
			'username' => 'username_api1.domain.com',
			'password' => 'THGSWS658IKUN79S',
			'signature' => 'AFYn4irhcVyzOOiJkc.H2zPIuztlArzO7mr5uXMO6DLICAE85JF.H5PPp',
			'endpoint' => 'https://api-3t.paypal.com/nvp',
			'version' => '53.0',
		),
	);
```

Load the Component into the controller of your choice.
```
	public $components = array(
		'PaypalWPP.PaypalWPP',
	);
```
CakePHP's HTTPSOCKET Object urlencodes everything for us.  For doing payments using DoDirectPayment (https://developer.paypal.com/webapps/developer/docs/classic/api/merchant/DoDirectPayment_API_Operation_NVP/) the following example would work:

```
	public function add() {
		if ($this->request->is('post') || $this->request->is('put')) {	
			$payment_information = $this->request->data['Sale'];
			
			$nvp = '&PAYMENTACTION=Sale';
			$nvp .= '&AMT='.$payment_information['amount'];
			$nvp .= '&CREDITCARDTYPE='.$payment_information['card_type'];
			$nvp .= '&ACCT='.$payment_information['card_number'];
			$nvp .= '$CVV2='.$payment_information['cvv2'];
			$nvp .= 'EXPDATE='.$payment_information['expiration_month'].$payment_information['expiration_year'];
			$nvp .= '&FIRSTNAME='.$payment_information['first_name'];
			$nvp .= '&LASTNAME='.$payment_information['last_name'];
			$nvp .= '&COUNTRYCODE=US&CURRENCYCODE=USD';
			
			$response = $this->PaypalWPP->wpp_hash('DoDirectPayment', $nvp);
			die();
			if ($response['ACK'] == 'Success') {
				$this->Session->setFlash('Payment Successful');
			} else {
				$this->Session->setFlash('Payment Failed');
			}
			debug($response);
		}	
	}
```

Other Methods can be found at https://devtools-paypal.com/apiexplorer/PayPalAPIs
