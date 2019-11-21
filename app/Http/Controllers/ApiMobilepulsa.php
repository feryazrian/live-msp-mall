<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiMobilepulsa extends Controller
{
    protected $username;
    protected $api_key;

    public function __construct()
    {
        $this->username = "085261040068";
        $this->api_key = "2985cb012ddc9910";
    }

    // Check Balance
    public function balance()
    {
        // Json
        $json = array(
            'commands' => 'balance',
            'username' => $this->username,
            'sign' => md5($this->username.$this->api_key.'bl'),
        );
        $json = json_encode($json);

        // Endpoint
        $client = new Client(['base_uri' => 'https://testprepaid.mobilepulsa.net']);

        $request = $client->request('POST', '/v1/legacy/index', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $json
        ]);

        // 200
        $status = $request->getStatusCode();

        // 'application/json; charset=utf8'
        $content_type = $request->getHeaderLine('content-type');

        // json
        $body = $request->getBody();
        $body = json_decode($body);

        return $body;
    }

    // Pre Paid - Price List
    public function pricelist($type, $operator)
    {
        // Json
        $json = array(
            'commands' => 'pricelist',
            'username' => $this->username,
            'sign' => md5($this->username.$this->api_key.'pl'),
            'status' => 'active',
        );
        $json = json_encode($json);

        // Endpoint
        $client = new Client(['base_uri' => 'https://testprepaid.mobilepulsa.net']);

        $request = $client->request('POST', '/v1/legacy/index/'.$type.'/'.$operator, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $json
        ]);

        // 200
        $status = $request->getStatusCode();

        // 'application/json; charset=utf8'
        $content_type = $request->getHeaderLine('content-type');

        // json
        $body = $request->getBody();
        $body = json_decode($body);

        return $body;
    }

    // Pre Paid - Top Up Request
    public function topup($ref_id, $hp, $pulsa_code)
    {
        // Json
        $json = array(
            'commands' => 'topup',
            'username' => $this->username,
            'sign' => md5($this->username.$this->api_key.$ref_id),

            'ref_id' => $ref_id,
            'hp' => $hp,
            'pulsa_code' => $pulsa_code,
        );
        $json = json_encode($json);

        // Endpoint
        $client = new Client(['base_uri' => 'https://testprepaid.mobilepulsa.net']);

        $request = $client->request('POST', '/v1/legacy/index', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $json
        ]);

        // 200
        $status = $request->getStatusCode();

        // 'application/json; charset=utf8'
        $content_type = $request->getHeaderLine('content-type');

        // json
        $body = $request->getBody();
        $body = json_decode($body);

        return $body;
    }

    // Pre Paid - Check Status
    public function inquiry($ref_id)
    {
        // Json
        $json = array(
            'commands' => 'inquiry',
            'username' => $this->username,
            'sign' => md5($this->username.$this->api_key.$ref_id),

            'ref_id' => $ref_id,
        );
        $json = json_encode($json);

        // Endpoint
        $client = new Client(['base_uri' => 'https://testprepaid.mobilepulsa.net']);

        $request = $client->request('POST', '/v1/legacy/index', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $json
        ]);

        // 200
        $status = $request->getStatusCode();

        // 'application/json; charset=utf8'
        $content_type = $request->getHeaderLine('content-type');

        // json
        $body = $request->getBody();
        $body = json_decode($body);

        return $body;
    }

    // Pre Paid - Check PLN Prepaid Subscriber
    public function inquiry_pln($hp)
    {
        // Json
        $json = array(
            'commands' => 'inquiry_pln',
            'username' => $this->username,
            'sign' => md5($this->username.$this->api_key.$hp),

            'hp' => $hp,
        );
        $json = json_encode($json);

        // Endpoint
        $client = new Client(['base_uri' => 'https://testprepaid.mobilepulsa.net']);

        $request = $client->request('POST', '/v1/legacy/index', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $json
        ]);

        // 200
        $status = $request->getStatusCode();

        // 'application/json; charset=utf8'
        $content_type = $request->getHeaderLine('content-type');

        // json
        $body = $request->getBody();
        $body = json_decode($body);

        return $body;
    }


    // Post Paid - Price List
    public function pricelist_pasca($type, $province = null)
    {
        // Json
        $json = array(
            'commands' => 'pricelist-pasca',
            'username' => $this->username,
            'sign' => md5($this->username.$this->api_key.'pl'),
            'status' => 'active',
            'province' => $province,
        );
        $json = json_encode($json);

        // Endpoint
        $client = new Client(['base_uri' => 'https://testpostpaid.mobilepulsa.net']);

        $request = $client->request('POST', '/api/v1/bill/check/'.$type, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $json
        ]);

        // 200
        $status = $request->getStatusCode();

        // 'application/json; charset=utf8'
        $content_type = $request->getHeaderLine('content-type');

        // json
        $body = $request->getBody();
        $body = json_decode($body);

        return $body;
    }

    // Post Paid - Inquiry Pascabayar
    public function inq_pasca($ref_id, $code, $hp)
    {
        // Json
        $json = array(
            'commands' => 'inq-pasca',
            'username' => $this->username,
            'sign' => md5($this->username.$this->api_key.$ref_id),

            'ref_id' => $ref_id,
            'code' => $code,
            'hp' => $hp,
        );
        $json = json_encode($json);

        // Endpoint
        $client = new Client(['base_uri' => 'https://testpostpaid.mobilepulsa.net']);

        $request = $client->request('POST', '/api/v1/bill/check', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $json
        ]);

        // 200
        $status = $request->getStatusCode();

        // 'application/json; charset=utf8'
        $content_type = $request->getHeaderLine('content-type');

        // json
        $body = $request->getBody();
        $body = json_decode($body);

        return $body;
    }

    // Post Paid - Payment Pascabayar
    public function pay_pasca($tr_id)
    {
        // Json
        $json = array(
            'commands' => 'pay-pasca',
            'username' => $this->username,
            'sign' => md5($this->username.$this->api_key.$tr_id),

            'tr_id' => $tr_id,
        );
        $json = json_encode($json);

        // Endpoint
        $client = new Client(['base_uri' => 'https://testpostpaid.mobilepulsa.net']);

        $request = $client->request('POST', '/api/v1/bill/check', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $json
        ]);

        // 200
        $status = $request->getStatusCode();

        // 'application/json; charset=utf8'
        $content_type = $request->getHeaderLine('content-type');

        // json
        $body = $request->getBody();
        $body = json_decode($body);

        return $body;
    }

    // Post Paid - Download Receipt
    public function download($tr_id)
    {
        // Endpoint
        $download = 'https://testpostpaid.mobilepulsa.net/api/v1/download/'.$tr_id;

        return $download;
    }

    // Post Paid - Check Status
    public function checkstatus($ref_id)
    {
        // Json
        $json = array(
            'commands' => 'checkstatus',
            'username' => $this->username,
            'sign' => md5($this->username.$this->api_key.'cs'),

            'ref_id' => $ref_id,
        );
        $json = json_encode($json);

        // Endpoint
        $client = new Client(['base_uri' => 'https://testpostpaid.mobilepulsa.net']);

        $request = $client->request('POST', '/api/v1/bill/check', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $json
        ]);

        // 200
        $status = $request->getStatusCode();

        // 'application/json; charset=utf8'
        $content_type = $request->getHeaderLine('content-type');

        // json
        $body = $request->getBody();
        $body = json_decode($body);

        return $body;
    }
}
