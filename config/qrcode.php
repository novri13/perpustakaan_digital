<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Qr Code Format
    |--------------------------------------------------------------------------
    |
    | This defines the default format for the generated QR codes.
    |
    */

    'format' => 'png',

    /*
    |--------------------------------------------------------------------------
    | Qr Code Size
    |--------------------------------------------------------------------------
    |
    | The default size of the QR code in pixels.
    |
    */

    'size' => 200,

    /*
    |--------------------------------------------------------------------------
    | Qr Code Writer
    |--------------------------------------------------------------------------
    |
    | This determines which backend writer to use.
    | Available options:
    | - \SimpleSoftwareIO\QrCode\Renderers\Image\GdImageBackEnd
    | - \SimpleSoftwareIO\QrCode\Renderers\Image\ImagickImageBackEnd
    |
    */

    'writer' => \SimpleSoftwareIO\QrCode\Renderers\Image\GdImageBackEnd::class,

];
