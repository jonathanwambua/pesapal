<?php

namespace Jonathanwambua\Pesapal;

use Jonathanwambua\Pesapal\Exceptions\PesapalException;
use App\Http\Controllers\Controller;
use Pesapal;

class PesapalController extends Controller
{
    public function handleCallback()
    {
        $order_tracking_id = request('OrderTrackingId');
        $order_notification_type = request('OrderNotificationType');
        $order_merchant_reference = request('OrderMerchantReference');
        $route = config('pesapal.callback_route');
        return redirect()->route(
            $route,
            array(
                'order_tracking_id' => $order_tracking_id,
                'order_notification_type' => $order_notification_type,
                'order_merchant_reference' => $order_merchant_reference
            )
        );
    }

    public function handleIpnTrigger()
    {
        
    }
}