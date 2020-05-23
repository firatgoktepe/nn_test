<?php

/**
 * Mailchimp functions
 *
 * @package Nax_Custom
 */

// Mailchimp integration based on code from: https://rudrastyh.com/mailchimp-api/subscription.html
function set_mailchimp_subscriber_status($email, $status, $list_id, $api_key, $merge_fields = array('FNAME' => '', 'LNAME' => ''))
{
    $data = array(
        'apikey'        => $api_key,
        'email_address' => $email,
        'status'        => $status, //subscribed, unsubscribed, cleaned, pending
        'merge_fields'  => $merge_fields
    );
    $mch_api = curl_init(); // initialize cURL connection

    curl_setopt($mch_api, CURLOPT_URL, 'https://'
        . substr($api_key, strpos($api_key, '-') + 1)
        . '.api.mailchimp.com/3.0/lists/' . $list_id
        . '/members/'
        . md5(strtolower($data['email_address'])));
    curl_setopt($mch_api, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic ' . base64_encode('user:' . $api_key)));
    curl_setopt($mch_api, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
    curl_setopt($mch_api, CURLOPT_RETURNTRANSFER, true); // return the API response
    curl_setopt($mch_api, CURLOPT_CUSTOMREQUEST, 'PUT'); // method PUT
    curl_setopt($mch_api, CURLOPT_TIMEOUT, 10);
    curl_setopt($mch_api, CURLOPT_POST, true);
    curl_setopt($mch_api, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($mch_api, CURLOPT_POSTFIELDS, json_encode($data)); // send data in json

    $result = curl_exec($mch_api);
    return $result;
}
function ajax_mailchimp_subscribe()
{
    $list_id = (isset($_POST['audience_id']) ? $_POST['audience_id'] : '');
    //'ce3bf668da';
    $api_key = '3d73061ed2e996b9bd61c615e8b0f53b-us4';
    $merge_fields = array();
    $merge_fields += ['FNAME' => (isset($_POST['fname']) ? $_POST['fname'] : '')];
    $merge_fields += ['LNAME' => (isset($_POST['lname']) ? $_POST['lname'] : '')];
    $result = json_decode(set_mailchimp_subscriber_status(
        $_POST['email'],
        'subscribed',
        $list_id,
        $api_key,
        $merge_fields
    ));
    // print_r( $result ); 
    if ($result->status == 400) {
        echo 'Status: ' . $result->status . '<br/>';
        echo $result->title . '<br/>';
        echo $result->detail . '<br/>';
    } elseif ($result->status == 'subscribed') {
        echo 'Tack! Du är nu registrerad.';
    }
    // $result['id'] - Subscription ID
    // $result['ip_opt'] - Subscriber IP address
    die;
}
add_action('wp_ajax_mailchimpsubscribe', 'ajax_mailchimp_subscribe');
add_action('wp_ajax_nopriv_mailchimpsubscribe', 'ajax_mailchimp_subscribe');
