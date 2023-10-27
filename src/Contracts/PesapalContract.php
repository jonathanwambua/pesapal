<?php

namespace Jonathanwambua\Pesapal\Contracts;

/**
 * Interface Pesapal Contract
 * @package Jonathanwambua\Pesapal\Contracts
 */
interface PesapalContract
{
    const PESAPAL_STATUS_INVALID = 'invalid';
    const PESAPAL_STATUS_COMPLETED = 'completed';
    const PESAPAL_STATUS_FAILED = 'failed';
    const PESAPAL_STATUS_REVERSED = 'reversed';
    
    public function submitOrder(Array $orderDetails);
    public function getMerchantStatus(String $order_tracking_id);
}