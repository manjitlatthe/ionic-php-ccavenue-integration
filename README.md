# ionic-php-ccavenue-integration
Non-Seamless integration with CCAvenue for Android through IONIC and php

# PHP
Firstly,

Enable API support for your CCavenue merchant account by Whitelist your outgoing IP address xxx.xx.xx.xxx for API calls.
Send the request regarding this to service@ccavenue.com with your valid Merchant ID.

Download all the file into "backend" folder from above repository and do the following changes -
- change the $working_id, $access_code, $merchant_id & $base_url in constant.php.
- $base_url should be the valid path where backend folder is placed.

Upload the "backend" folder to the Whitelisted outgoing IP hosting server.

# IONIC

Create your new ionic project from https://ionicframework.com/docs/overview/.
Then install inappbrowser by executing command -

$ cordova plugin add cordova-plugin-inappbrowser

After successful installation write the following code in the controller -

  	$scope.data = {
		orderId: 1,
		amount: 1,
	};
  
	$scope.ref = null;
	$scope.getStateSecondWindow = function() 
	{
		$scope.ref.executeScript(
	        {code: "localStorage.getItem('isCloseSelf')"},
	        function(data)
	        {
	        	if (data == 'yes')
                {
	        		$scope.ref.close();
                } 
	        }
	    );
	}
	$scope.onSubmit = function(){
		$scope.ref = window.open(CONFIG.BASE_URL + 'getRSA.php?orderId=' + $scope.data.orderId + '&amount=' + $scope.data.amount,'_blank','location=no');
		$scope.ref.addEventListener('loadstart', function(event) {  });
		$scope.ref.addEventListener('loadstop', function(event) {
			setInterval($scope.getStateSecondWindow, 5000);
		});
		$scope.ref.addEventListener('exit', function(event) {  });
	}
  
  You can change the orderId and amount as you required. 
  
  Also don't forget to change CONFIG.BASE_URL value. It should be a valid server URL where getRSA.php is placed.

All the best.

<!-- Demo link: <a href="http://demos.masterequation.com/manjit/ionic-php-ccavenue-integration/myproject/www/" target="_blank">Click here</a> -->
