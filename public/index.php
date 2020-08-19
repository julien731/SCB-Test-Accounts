<?php
require('../vendor/autoload.php');
use GuzzleHttp\Client;

$access_token = filter_input( INPUT_POST, 'access_token', FILTER_SANITIZE_STRING );
$owner_id = filter_input( INPUT_POST, 'owner_id', FILTER_SANITIZE_STRING );
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="SCB Test Accounts">
	<title>SCB Test Accounts</title>
	<link rel="stylesheet" href="https://unpkg.com/purecss@2.0.3/build/pure-min.css" integrity="sha384-cg6SkqEOCV1NbJoCu11+bm0NvBRc8IYLRGXkmNrqUBfTjmMYwNKPWBTIKyw9mHNJ" crossorigin="anonymous">
</head>
<body>
	<div id="layout">
		<div id="main">
			<div class="content">

            <?php
            if ( ! empty( $access_token ) && ! empty( $owner_id ) ) :

                $client = new Client([
                    // Base URI is used with relative requests
                    'base_uri' => 'https://api-sandbox.partners.scb/partners/sandbox/v2/',
                ]);

                // Prepare headers.
                $headers = [
                    'authorization' =>  'Bearer ' . $access_token,
                    'resourceOwnerId' => $owner_id,
                    'requestUId' => '123456'
                ];

                $customer = $client->request(
                    'GET',
                    'customers/profile',
		                [
		                		'headers' => $headers
		                ]
                );

                $customer_response = json_decode( $customer->getBody(), true );
                echo '<pre><code>';
                print_r( $customer_response );
                echo '</code></pre>';

			elseif( isset( $_GET['qr'] ) ) :

				$client = new Client([
					// Base URI is used with relative requests
					'base_uri' => 'https://merchant-tw.gcp-merchant-staging.omiselabs.dev/',
				]);

				// Prepare headers.
				$headers = [
					'Authorization' => 'MerchantModule key_8ba2fc45:b6e4828bd505903cd7377c8711d3e105d18cd23b6063424251823676c64230e7'
				];

				$payment = $client->request(
					'POST',
					'api/v1/invoices',
					[
						'headers' => $headers,
						'body' => '{
    "acqMerchantId": "STAGING001",
    "acqRequesterId": "DTG00001",
    "ref1": "ref1 by kat",
    "amount": "100.00",
    "currency": "THB",
    "ref2": "test",
    "description": "description",
    "callBackUrl": "" ,
    "returnUri": ""
}'
					]
				);

				$response = json_decode( $payment->getBody(), true );

				printf( '<a href="%1$s">%1$s</a>', $response['data']['deepLink'] );

				echo '<img src="data:image/gif;base64,' . $response['data']['qr'] . '" />';

			else:
            ?>

			<h1>SCB Test Accounts</h1>

			<form method="POST" action="/" class="pure-form">
				<fieldset class="pure-group">
				  <label for="access_token">Access Token:</label><br>
				  <input type="text" id="access_token" name="access_token">
				</fieldset>
				<fieldset class="pure-group">
				  <label for="owner_id">Resource Owner ID:</label><br>
				  <input type="text" id="owner_id" name="owner_id">
				</fieldset>
			  <input type="submit" value="Check" class="pure-button pure-input-1-2 pure-button-primary">
			</form>

            <?php endif; ?>

		</div>
	</div>
</div>
</body>
</html>
