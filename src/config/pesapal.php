<?php

return [
    /**
     * Pesapal consumer key
     */
    'consumer_key' => env('PESAPAL_CONSUMER_KEY'),

    /**
     * Pesapal consumer secret
     */
    'consumer_secret' => env('PESAPAL_CONSUMER_SECRET'),

    /**
     * Default Currency: ISO Currency Codes https://
     */
    'currency' => env('PESAPAL_DEFAULT_CURRENCY', 'KES'),

    /**
     * Pesapal environment
     */
    'live' => env('PESAPAL_LIVE', false),

    /**
     * Pesapal IPN Route
     * Route name to be called by pesapal when a payment status changes
     * eg; "TransactionController@confirmation"
     */
    'ipn' => env('PESAPAL_IPN_URL'),

    /**
     * Route name to handle callback after a payment request is posted
     * eg; "Payment@paymentsuccess"
     */
    'callback_url' => env('PESAPAL_CALLBACK_URL', 'http://localhost'),

    /**
     * Route name to be called when a user cancels payment
     * 
     */
    'cancellation_url' => env('PESAPAL_CANCELLATION_URL')
];