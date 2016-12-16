<?php
require 'constant.php';
require 'Crypto/phpseclib/Crypt/RSA.php';

$orderId = $_GET['orderId'];
$amount = $_GET['amount'];

$responseURL = $base_url.'backend/ccavMobileResponseHandler.php';
$url = "https://secure.ccavenue.com/transaction/getRSAKey";
$fields = array(
	'access_code'=>$access_code,
	'order_id'=>$orderId,
);
//
$postvars = '';
$sep = '';
foreach($fields as $key => $value)
{
	$postvars .= $sep.urlencode($key).'='.urlencode($value);
	$sep = '&';
}
//
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST,count($fields));
curl_setopt($ch, CURLOPT_CAINFO, 'Crypto/cacert.pem');
curl_setopt($ch, CURLOPT_POSTFIELDS,$postvars);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$key = curl_exec($ch);
//
$plain_data  = '';
$plain_data .= 'currency=INR&';
$plain_data .= 'amount='.$amount.'&';
//
$key = base64_decode($key);
$rsa = new Crypt_RSA();
$rsa->loadKey($key); // public key
$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
$ciphertext = $rsa->encrypt($plain_data);
$encrypted_data = base64_encode($ciphertext);
/**/
$merchant_data  = '';
$merchant_data .= 'order_id='.$orderId.'&';
$merchant_data .= 'enc_val='.urlencode($encrypted_data).'&';
$merchant_data .= 'redirect_url='.$responseURL.'&';
$merchant_data .= 'cancel_url='.$responseURL.'&';
$merchant_data .= 'merchant_id='.$merchant_id.'&';
$merchant_data .= 'access_code='.$access_code.'&';
// 		echo utf8_encode($merchant_data);
$data = utf8_encode($merchant_data);
?>
<html>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
	<head>
		<title>Payment Request</title>
	</head>
<body>
	<center>
		<form id="frm" method="post" action="https://secure.ccavenue.com/transaction/initTrans?<?php echo $data; ?>>">
		</form>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script type="text/javascript">
		    	$(document).ready(function(){
		    		localStorage.setItem('isCloseSelf', 'no');
		    		$('form#frm').submit();
				});
		</script>
	</center>
</body>
</html>