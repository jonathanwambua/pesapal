<?php

namespace Jonathanwambua\Pesapal;

use Jonathanwambua\Pesapal\Contracts\PesapalContract;
use Jonathanwambua\Pesapal\Exceptions\PesapalException;
use Jonathanwambua\Pesapal\App\Helpers\PesapalV3Helper;
use Route;
use stdClass;

class Pesapal implements PesapalContract
{
    private $api = null;

    public function __construct()
    {
        $this->api = config('pesapal.live') ? 'live' : 'demo';
    }

    /**
     * @param $orderDetails
     * @return string A iframe url for the payment
     * @throws \Wambua\Pesapal\Exceptions\PesapalException
     */
    public function submitOrder($orderDetails)
    {
        $pesapalV3Helper = new PesapalV3Helper($this->api);

        if (!array_key_exists('currency', $orderDetails))
        {
            if (config('pesapal.currency') != null)
            {
                $orderDetails['currency'] = config('pesapal.currency');
            }
        }

        if (!config('pesapal.callback_url'))
        {
            throw new PesapalException("callback route not provided");
        } 
        // else {
        //     if (!Route::has(config('pesapal.callback_url')))
        //     {
        //         throw new PesapalException("callback route does not exist");
        //     }
        // }

        $token = null;
        $consumer_key = config('pesapal.consumer_key');
        $consumer_secret = config('pesapal.consumer_secret');
        $callback_url = url('/').'/pesapal-callback'; //this will handle response from pesapal
        $ipn_url = config('pesapal.ipn') ? config('pesapal.ipn') : url('/pesapal-ipn');
        $unique_id = time().'-'.mt_rand();

        $token = $pesapalV3Helper->getAccessToken($consumer_key, $consumer_secret);
        
        if ($token)
        {
            $registered_ipn = $pesapalV3Helper->getRegisteredIpn($token);
            // auto register IPN
            $ipn_id = null;
            for ($i=0; $i<count($registered_ipn); $i++)
            {
                if ($registered_ipn[$i]->url == $ipn_url)
                {
                    $ipn_id = $registered_ipn[$i]->ipn_id;
                }
            }
            if (!$ipn_id) { $ipn_id = $pesapalV3Helper->generateNotificationId($ipn_url, $token); }

            $request = new stdClass();
            $request->merchant_reference = $orderDetails['reference'];
            $request->currency = $orderDetails['currency'];
            $request->amount = $orderDetails['amount'];
            $description = $orderDetails['description'];
            $request->description = trim(urldecode(html_entity_decode(strip_tags($description))));
            $request->description = str_replace(array('(', ')'), '', htmlentities(substr($request->description, 0, 99))); //limit 100 chars
            $request->redirect_mode = isset($orderDetails['redirect_mode']) ? $orderDetails['redirect_mode'] : "";
            $request->callback_url = $callback_url;
            $request->cancellation_url = config('pesapal.cancellation_url') ? config('pesapal.cancellation_url') : "";
            $request->notification_id = $ipn_id;

            $request->billing_phone = $orderDetails['phonenumber'];
            $request->billing_email = $orderDetails['email'];
            $request->billing_first_name = $orderDetails['first_name'];
            $request->billing_first_name = $orderDetails['last_name'];

            $order_response = $pesapalV3Helper->getMerchertOrderURL($request, $token);

            return '<iframe src="' . $order_response->redirect_url . '" width="' . $orderDetails['width'] . '" height="' . $orderDetails['height'] . '" scrolling="auto" frameBorder="0"> <p>Unable to load the payment page</p> </iframe>';
        } else {
             throw new PesapalException("Post Order: token does not exist");
        }
    }

    public function getMerchantStatus($orderTrackingId)
    {
        $consumer_key = config('pesapal.consumer_key');
        $consumer_secret = config('pesapal.consumer_secret');
        
        $token = null;
        $token = $pesapalV3Helper->getAccessToken($consumer_key, $consumer_secret);
        if ($token)
        {
            $transaction_status = $pesapalHelper->getTransactionStatus($orderTrackingId, $access_token);
        } else {
            throw new PesapalException("Get Status: token does not exist");
        }

        $payment_status = $transaction_status->status_code;
        if ($payment_status === 1) //completed
            $response = 'completed';
        if ($payment_status === 2) //Failed
            $response = 'failed';
        if ($payment_status === 3) //Reversed
            $response = 'reversed';
        if ($payment_status === 0) //Invalid
            $response = 'invalid';
        
        return $response;
    }
}