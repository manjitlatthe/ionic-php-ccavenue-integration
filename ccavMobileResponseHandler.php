<?php
require 'constant.php';
require 'Crypto/Crypto.php';
error_reporting(0);

$encResponse = $_POST["encResp"];			//This is the response sent by the CCAvenue Server
$rcvdString = decrypt($encResponse,$working_key);		//Crypto Decryption used as per the specified working key.
$order_status = "";
$decryptValues = explode('&', $rcvdString);
$dataSize = sizeof($decryptValues);

//
$response = '';
$orderId = 0;
for($i = 0; $i < $dataSize; $i++)
{
	$information=explode('=',$decryptValues[$i]);
	if($i==3)	$order_status=$information[1];
}
	 
if($order_status==="Success")
{
	$response .= "<br/>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
}
else if($order_status==="Aborted")
{
	$response .= "<br/>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
}
else if($order_status==="Failure")
{
	$response .= "<br/>Thank you for shopping with us.However,the transaction has been declined.";
}
else
{
	$response .= "<br/>Security Error. Illegal access detected";
}

$response .= "<br/><br/>";

$response .= "<table cellspacing=4 cellpadding=4>";
for($i = 0; $i < $dataSize; $i++)
{
	$information=explode('=',$decryptValues[$i]);
	$response .= '<tr><td>'.$information[0].'</td><td>'.$information[1].'</td></tr>';
}

$response .= "</table><br>";
?>
<html>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
	<head>
		<title>Payment Response</title>
	</head>
<body>
	<center>
		<?php echo $response; ?>
		<br/>
		<button id="btnClose">Back</button>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script type="text/javascript">
		     	$(document).ready(function(){
					$('#btnClose').on('click', function(e){
						localStorage.setItem('isCloseSelf', 'yes');
						e.preventDefault();
					});
		 		});
		</script>
	</center>
</body>
</html>
