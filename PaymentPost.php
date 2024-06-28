<?php 

$orderid = "21";
$merchant = "TEST0099123456";
$apipassword = "d443b811068a15df31da54291c25ff88";
$amount = "100.00";
$returnUrl = "http://localhost/test1/Tepmplates/MasterCardGateway/PaymentPost.php";
$currency = "LKR";
$url ="https://test-gateway.mastercard.com/api/rest/version/81/merchant/TEST0099123456/session";

$data_array = array(
    'apiOperation' => "CREATE_CHECKOUT_SESSION",
    'apiPassword' => $apipassword,
    'returnUrl' => $returnUrl,
    'apiUsername' => 'merchant.'.$merchant,
    'id' => $orderid,
    'currency' => $currency
);

// $data= array(
//     "session" => array(
//         "authenticationLimit" => 25
//     )
// );

$data = array(
    "apiOperation" => "INITIATE_CHECKOUT",
    "interaction" => array(
        "operation" => "PURCHASE",
        "merchant" => array(
            "name" => "TestRMerchant01"
        )
    ),
    "order" => array(
        "currency" => "LKR",
        "amount" => "200.00",
        "id" => "1011",
        "description" => "RADIO"
    )
); 
 
$ch = curl_init();

  $new_data = json_encode($data);
 
  //options for curl
  $array_options = array(
    CURLOPT_URL=>$url,
    CURLOPT_POST=>true,
    CURLOPT_POSTFIELDS=>$new_data,
      
    CURLOPT_RETURNTRANSFER=>true,
    curl_setopt($ch, CURLOPT_USERPWD, "merchant.".$merchant . ":" . $apipassword), 
    CURLOPT_HTTPHEADER=>array('Content-Type:application/x-www-form-urlencoded')
  );

  curl_setopt_array($ch,$array_options);
  $resp = curl_exec($ch);

    $final_decoded_data = json_decode($resp,true);
    //print_r($final_decoded_data);
    //print_r($resp);
    $sessionid = $final_decoded_data["session"]["id"];

  curl_close($ch);
  ?>
    <script
        src="https://test-gateway.mastercard.com/static/checkout/checkout.min.js"
        data-error="errorCallback"
        data-cancel=""></script>
   
    <script type="text/javascript">
            function errorCallback(error){
                alert("Error: " + JSON.stringify(error));
                window.location.href = "http://localhost/test1/Tepmplates/MasterCardGateway/PaymentPost.php";
            }

            Checkout.configure({
                session:{
                    id: '<?php echo $sessionid; ?>'
                }
            })
            Checkout.showPaymentPage();
    </script>
