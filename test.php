<?php

include "vendor/autoload.php";
include "BitPayClient.php";

$client = new BitPayClient();

# Call this function to create the Private Public and Merchant Pairing Code to claim on the BitPay Console.
# This function call is done only once to create the tokens. Once the tokens are created, set them on the
# variables from Line 14-17
# Since we create a merchant Token, we also need to claim the Pairing Token that we receive on the output
# under the API Tokens section on the Bitpay Web Console.
//$client->createTokenAsset();

# Code below has to be executed only after claiming the Merchant Token on the Bitpay Console.

# Set the values from the output from the execution on Line 9.
//$private_key   = 'C:17:"Bitpay\PrivateKey":189:{a:5:{i:0;N;i:1;N;i:2;N;i:3;s:64:"60db0b2bf76ffcce3a4bb3de207789a4c293b8787cfd7ffba8e07042348d3306";i:4;s:77:"43809050075725765872195840552439125283497992039866323583721405497479158117126";}}';
//$public_key    = 'C:16:"Bitpay\PublicKey":472:{a:5:{i:0;N;i:1;s:64:"b48dccc91b56f799302d64b12990624534f8075773856b83334995a3503a4b5f";i:2;s:64:"2d32461aacccffee4f96a6ddc6fc1acf52367341b273b3881b21270677c354ab";i:3;s:128:"b48dccc91b56f799302d64b12990624534f8075773856b83334995a3503a4b5f2d32461aacccffee4f96a6ddc6fc1acf52367341b273b3881b21270677c354ab";i:4;s:154:"9456375363474113172812320300439242756289022641298856668046409802494316840854686841353936224511617159543466710928285993383739333984030165759699528118916267";}}';
//$pairing_code  = "f4YmfMW";
//$pairing_token = "46LZTtmatKVcoFnqy5e4Rw";

# This function sets up all the further calls to have the required credentials for all API calls
//$client->tokenize($private_key, $public_key, $pairing_token);

# Creates a new Invoice and outputs the invoice id and the invoice URL for the customer
# Once an invoice has been generated, comment this line.
# Go to the URL output from this function call and make the payment for this invoice.
//$client->generateInvoice();

# Once the payment to the invoice has been successfully completed, mention the invoice ID and the address to which the amount should be refunded to
//$client->createRefund("Pc6YUeXPh5y4u1s2viFYNm", "2NEwbnfMaLZeu4Xfs3wqUH66TRZR1TF7VAz", "0.0289", "BTC");