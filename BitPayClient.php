<?php


use Bitpay\Client\Adapter\CurlAdapter;
use Bitpay\Client\Client;
use Bitpay\Currency;
use Bitpay\Invoice;
use Bitpay\Item;
use Bitpay\Network\Testnet;
use Bitpay\PrivateKey;
use Bitpay\PublicKey;
use Bitpay\SinKey;
use Bitpay\Token;

class BitPayClient
{
    private $_client;
    private $_network;
    private $_adapter;
    private $_privateKey;
    private $_publicKey;
    private $_pairingToken;

    /**
     * The parameter on the constructor will be set to True only once if we are setting up the Key Pairs
     * and configuring the Pairing Code for the BitPay Integration
     *
     * By Default the variable $setup is set to `false` so that the variables can be initialized for regular integration
     * calls like CreateInvoice, GetInvoice, etc.
     *
     * The network is set to LiveNet or TestNet based on the environment that we execute this class
     * If the environment is not Production, then it will always use the TestNet.
     *
     */
    public function __construct()
    {
        $this->_client = new Client();

        $this->network  = new Testnet();
        $this->_network = $this->network;

        $this->adapter  = new CurlAdapter();
        $this->_adapter = $this->adapter;
    }

    /**
     * This function will be called only when you want to setup the Key Pairs for the BitPay Integration
     * This function will not execute if the Pairing Token already exists in the database.
     *
     * @throws \Exception
     */
    public function createTokenAsset()
    {
        $this->_privateKey = PrivateKey::create()->generate();

        echo 'Private Key is: ' . serialize($this->_privateKey) . PHP_EOL;
        $this->_publicKey = new PublicKey();
        // Inject the private key into the public key
        $this->_publicKey->setPrivateKey($this->_privateKey);
        // Generate the public key
        $this->_publicKey->generate();

        echo 'Public Key is: ' . serialize($this->_publicKey) . PHP_EOL;

        $this->_client->setPrivateKey($this->_privateKey);
        $this->_client->setPublicKey($this->_publicKey);
        $this->_client->setNetwork($this->_network);
        $this->_client->setAdapter($this->_adapter);

        $sin = new SinKey();
        $sin = $sin->setPublicKey($this->_publicKey)->generate();

        $token = $this->_client->createToken(
            [
                'id'     => (string)$sin,
                'label'  => 'App Pairing Token',
                'facade' => 'merchant'
            ]
        );

        $this->_pairingToken = $token->getPairingCode();
        echo "The Pairing Token is: " . $token->getPairingCode() . PHP_EOL;
        echo "Token after Pairing that will be used to create an Invoice is: " . $token->getToken() . PHP_EOL;
        return [
            'private_key'  => $this->_privateKey,
            'public_key'   => $this->_publicKey,
            'pairing_code' => $token->getPairingCode()
        ];
    }

    public function tokenize($privateKey, $publicKey, $token)
    {
        $this->_privateKey   = unserialize($privateKey);
        $this->_publicKey    = unserialize($publicKey);
        $this->_pairingToken = $token;
    }

    /**
     * Creates an Invoice
     */
    public function generateInvoice()
    {
        $invoice = new Invoice();

        $this->_client->setPrivateKey($this->_privateKey);
        $this->_client->setPublicKey($this->_publicKey);
        $this->_client->setNetwork($this->_network);
        $this->_client->setAdapter($this->_adapter);

        $token = new Token();
        $token->setToken($this->_pairingToken);
        $this->_client->setToken($token);

        $item = new Item();
        $item->setCode('skuNumber');
        $item->setDescription('General Description of Item');
        $item->setPrice('10');

        $invoice->setCurrency(new Currency('USD'));

        $invoice->setItem($item);
        $invoice->setFullNotifications(true);
        //$invoice->setNotificationUrl('http://9b76074.ngrok.com/web-hooks/bitcoin/payment-success');
        //$invoice->setRedirectUrl('http://9b76074.ngrok.com');

        try {
            $this->_client->createInvoice($invoice);
        } catch (\Exception $e) {
            echo 'Reached Catch with error . ' . $e->getMessage();
            $request  = $this->_client->getRequest();
            $response = $this->_client->getResponse();
            echo (string)$request . PHP_EOL . PHP_EOL . PHP_EOL;
            echo (string)$response . PHP_EOL . PHP_EOL;
            exit(1); // We do not want to continue if something went wrong
        }
        echo 'Invoice "' . $invoice->getId() . '" created, see ' . $invoice->getUrl() . PHP_EOL;
    }

    public function createRefund($invoiceId, $bitcoinAddress, $amount, $currency)
    {
        $this->_client->setPrivateKey($this->_privateKey);
        $this->_client->setPublicKey($this->_publicKey);
        $this->_client->setNetwork($this->_network);
        $this->_client->setAdapter($this->_adapter);

        $token = new Token();
        $token->setToken($this->_pairingToken);
        $this->_client->setToken($token);
        try {
            $data = $this->_client->createRefund($invoiceId, $bitcoinAddress, $amount, $currency);
            echo $data;
        } catch (\Exception $e) {
            echo 'Reached Catch with error . ' . $e->getMessage();
            $request  = $this->_client->getRequest();
            $response = $this->_client->getResponse();
            echo "Request: " . (string)$request . PHP_EOL . PHP_EOL . PHP_EOL;
            echo "Response: " . (string)$response . PHP_EOL . PHP_EOL;
            exit(1); // We do not want to continue if something went wrong
        }
    }
}