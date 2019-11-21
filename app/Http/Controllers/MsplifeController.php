<?php

namespace Marketplace\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class MsplifeController extends Controller
{
    protected $key = 'e5bd5a6acef1e4831aefa4e4bc2cf31e';

    public function __construct()
    {
        $this->key = env('MSPLIFE_APIKEY');
    }

    public function check_mspoint($operation, $username)
    {
        $parameters = [
            'key'           => $this->key,
            'operation'     => $operation,
            'username'      => $username,
        ];

        $client = new Client(['base_uri' => 'https://mymsplife.com']);

        $request = $client->request('POST', '/secure/api/mspmallv2.php', [
            'query' => $parameters
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

    public function update_mspoint($operation, $username, $point)
    {
        $parameters = [
            'key'           => $this->key,
            'operation'     => $operation,
            'username'      => $username,
            'point'         => $point,
        ];

        $client = new Client(['base_uri' => 'https://mymsplife.com']);

        $request = $client->request('POST', '/secure/api/mspmallv2.php', [
            'query' => $parameters
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
    
    public function deduct_mspoint($operation, $username, $point)
    {
        $parameters = [
            'key'           => $this->key,
            'operation'     => $operation,
            'username'      => $username,
            'point'         => $point,
        ];

        $client = new Client(['base_uri' => 'https://mymsplife.com']);

        $request = $client->request('POST', '/secure/api/mspmallv2.php', [
            'query' => $parameters
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
    
    public function create_user($operation, $username, $email, $password, $name)
    {
        $parameters = [
            'key'           => $this->key,
            'operation'     => $operation,
            'username'      => $username,
            'email'         => $email,
            'password'      => $password,
            'name'          => $name,
        ];

        $client = new Client(['base_uri' => 'https://mymsplife.com']);

        $request = $client->request('POST', '/secure/api/mspmallv2.php', [
            'query' => $parameters
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

    public function login_validation($operation, $email, $password)
    {
        $parameters = [
            'key'           => $this->key,
            'operation'     => $operation,
            'email'         => $email,
            'password'      => $password,
        ];

        $client = new Client(['base_uri' => 'https://mymsplife.com']);

        $request = $client->request('POST', '/secure/api/mspmallv2.php', [
            'query' => $parameters
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

    public function check_duplicate_username($operation, $username)
    {
        $parameters = [
            'key'           => $this->key,
            'operation'     => $operation,
            'username'      => $username,
        ];

        $client = new Client(['base_uri' => 'https://mymsplife.com']);

        $request = $client->request('POST', '/secure/api/mspmallv2.php', [
            'query' => $parameters
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

    public function check_duplicate_email($operation, $email)
    {
        $parameters = [
            'key'           => $this->key,
            'operation'     => $operation,
            'email'      => $email,
        ];

        $client = new Client(['base_uri' => 'https://mymsplife.com']);

        $request = $client->request('POST', '/secure/api/mspmallv2.php', [
            'query' => $parameters
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
