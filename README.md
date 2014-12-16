BitPay Issue Tests
==================

```
# Call this function to create the Private Public and Merchant Pairing Code to claim on the BitPay Console.
# This function call is done only once to create the tokens. Once the tokens are created, set them on the
# variables from Line 15-18
# Since we create a merchant Token, we also need to claim the Pairing Token that we receive on the output
# under the API Tokens section on the Bitpay Web Console.
$client->createTokenAsset();

# Code below has to be executed only after claiming the Merchant Token on the Bitpay Console.

# Set the values from the output of the execution on Line 10 and comment line 10
$private_key   = '';
$public_key    = '';
$pairing_code  = "";
$pairing_token = "";

# This function sets up all the further calls to have the required credentials for all API calls
$client->tokenize($private_key, $public_key, $pairing_token);

# Creates a new Invoice and outputs the invoice id and the invoice URL for the customer
# Once an invoice has been generated, comment this line.
# Go to the URL output from this function call and make the payment for this invoice.
$client->generateInvoice();

# Once the payment to the invoice has been successfully completed, mention the invoice ID and the address to which the amount should be refunded to
$invoice_id = "";
$btc_address = "";
$amount = "";
$currency = "";
$client->createRefund($invoice_id,$btc_address, $amount, $currency);
```
