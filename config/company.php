<?php

setlocale(LC_ALL, env('COMPANY_LOCALE', 'en_US'));
return [
    'locale'    => localeconv(),
    'admins'    => env('COMPANY_ADMINS', 'example.com'), // backend admin email domains
    'name'      => env('COMPANY_NAME','Company Name'),
    'legalname' => env('COMPANY_LEGALNAME', 'Company Legal, Inc.'),
    'slogan'    => env('COMPANY_SLOGAN', 'Company Slogan'),
    'hours'     => env('COMPANY_HOURS', 'Mon-Fri / 9:00AM - 5:00PM'),
    'domain'    => env('COMPANY_DOMAIN', 'example.com'),
    'url'       => env('COMPANY_URL', 'http://localhost'),
    'line1'     => env('COMPANY_LINE1', '1234 Example St.'),
    'line2'     => env('COMPANY_LINE2'),
    'city'      => env('COMPANY_CITY', 'Cityname'),
    'state'     => env('COMPANY_STATE', 'Statename'),
    'st'        => env('COMPANY_ST', 'ST'), // ABBREVIATION OF STATE
    'zip'       => env('COMPANY_ZIP', '85000'),
    'country'   => env('COMPANY_COUNTRY', 'USA'),
    'latitude'  => (double)env('COMPANY_LATITUDE', 40),
    'longitude' => (double)env('COMPANY_LONGITUDE', -100),
    'email'     => env('COMPANY_EMAIL', 'hello@example.com'),
    'phone'     => env('COMPANY_PHONE', '6X6-2X0-4XX0'),
    'tollfree'  => env('COMPANY_TOLLFREE', '800-123-1234'),
    'fax'       => env('COMPANY_FAX'),
];