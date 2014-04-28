<?php

/**
 * This class is used to generate a payment URL.
 */
class PaymentUrl {

    public $amount;
    public $currency;
    public $customData;
    public $customer;
    public $email;
    public $firstName;
    public $ipnUrl;
    public $lastName;
    public $order;
    public $origin;
    public $returnUrl;

    /**
     * The method which actually generates the URL.
     */
    public static function generateUrl($params) {
        $config = Payplug::getConfig();
        $data;
        $signature;

        // If the merchant's parameters have not been set
        if ( ! $config) {
            // Something bad will happen
            throw new ParametersNotSetException();
        }
        //TODO: check if all mandatory parameter are set and set other to default value if needed
        if ( ! preg_match("/^(http|https):\/\//i", $params['ipnUrl'])) {
            throw new MalformedURLException($params['ipnUrl'] . " doesn't starts with 'http://' or 'https://'");
        }
        if ($params['returnUrl'] != null && ! preg_match("/^(http|https):\/\//i", $params['returnUrl'])) {
            throw new MalformedURLException($params['returnUrl'] . " doesn't starts with 'http://' or 'https://'");
        }

        /* Generation of the <data> parameter */
        $url_params = http_build_query(array(
            "amount" => $params['amount'],
            "currency" => $params['currency'],
            "custom_data" => $params['customData'],
            "customer" => $params['customer'],
            "email" => $params['email'],
            "first_name" => $params['firstName'],
            "ipn_url" => $params['ipnUrl'],
            "last_name" => $params['lastName'],
            "order" => $params['order'],
            "origin" => $params['origin'] . " payplug-php" . Payplug::VERSION . " PHP" . phpversion(),
            "return_url" => $params['returnUrl']
        ));
        $data = urlencode(base64_encode($url_params));

        /* Generation of the <signature> parameter */
        $privateKey = openssl_pkey_get_private($config->privateKey);
        openssl_sign($url_params, $signature, $privateKey, OPENSSL_ALGO_SHA1);
        $signature = urlencode(base64_encode($signature));

        return $config->paymentBaseUrl . "?data=" . $data . "&sign=" . $signature;
    }
}