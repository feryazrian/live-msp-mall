<?php

namespace Marketplace\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\ResponseFactory;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param int $code
     * @param string $message
     * @param array $data
     * @param array $errors
     * @return void
     */
    public function boot(ResponseFactory $factory)
    {
        $factory->macro('api', function ($code, $message='', $data=[], $errors=null) use ($factory) {
            $status = $code < 300 ? 'OK' : 'ERROR';

            $defaultFormat = [
                'code'      => $code,
                'status'    => $status,
                'message'   => $message,
            ];

            if ($data) {
                $defaultFormat['items'] = $data;
            }

            if ($errors) {
                $defaultFormat['errors'] = $errors;
            }

            return $factory->make($defaultFormat);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
