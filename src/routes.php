<?php

Route::get('pesapal-callback', ['as'=>'pesapal-callback', 'uses'=>'Jonathanwambua\Pesapal\PesapalController@handleCallback']);
Route::get('pesapal-ipn', ['as'=>'pesapal-ipn', 'uses'=>'Jonathanwambua\Pesapal\PesapalController@handleIpnTrigger']);