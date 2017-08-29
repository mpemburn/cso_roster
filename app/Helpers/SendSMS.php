<?php
/**
 * SendSMS uses Simple SMS to send text message via email.
 * If carrier code is not supplied, it attempts to get it via Numbverify (https://numverify.com)
 *
 * NOTE: The Numverify API code must be in the .env file
 */

namespace App\Helpers;

use SimpleSoftwareIO\SMS\Facades\SMS;
use GuzzleHttp\Client;

class SendSMS {

    //TODO: Get the rest of the carriers
    protected $carriers = [
        'AT&T' => 'att',
        'Cricket' => 'cricket',
        'Sprint' => 'sprint',
        'T-Mobile' => 'tmobile',
        'Verizon' => 'verizonwireless',
        'Virgin' => 'virginmobile',
    ];

    /**
     * @param $phone
     * @param string $countryCode
     * @return string, null on not found
     */
    public function getCarrier($phone, $countryCode = 'US')
    {
        $carrier = null;

        $client = new Client();
        $res = $client->request('GET', env('NUMVERIFY_URL'), [
            'query' => [
                'access_key' => env('NUMVERIFY_API_KEY'),
                'country_code' => $countryCode,
                'number' => $phone,
                'format' => 1
            ]
        ]);
        $status = $res->getStatusCode();
        if ($status ==  '200') {
            $response =  $res->getBody();
            $responseArray = json_decode($response);
            if (!is_null($responseArray)) {
                $carrier = $responseArray->carrier ?? null;
            }
        }

        return $carrier;
    }

    /**
     * @param $carrierString
     * @return string, null on not found
     */
    public function getCarrierCode($carrierString)
    {
        $keys = array_keys($this->carriers);

        foreach ($keys as $key) {
            if (stristr($carrierString, $key) !== false) {
                return($this->carriers[$key]);
            }
        }

        return null;
    }

    /**
     * @param $phone
     * @param null $carrierCode
     * @return boolean
     */
    public function send($phone, $message, $carrierCode = null)
    {
        // Remove all but digits from the phone string
        $phone = preg_replace('/[^0-9\/]+/', '', $phone);
        if (strlen($phone) != 10) {
            return false;
        }
        // Try to get the carrier from the phone number
        if (is_null($carrierCode)) {
            $carrierCode = $this->getCarrier($phone);
            if (is_null($carrierCode)) {
                return false;
            }
        }
        $carrierCodes = array_values($this->carriers);
        // If the carrierCode is not in the array of valid codes,
        // try to get it from what's passed
        if (!in_array($carrierCode, $carrierCodes)) {
            $carrierCode = $this->getCarrierCode($carrierCode);
            if (is_null($carrierCode)) {
                return false;
            }
        }

        // We should have what we need -- send it!
        SMS::send($message, null, function($sms) use ($phone, $carrierCode) {
            $sms->to($phone, $carrierCode);
        });

        return true;
    }
}