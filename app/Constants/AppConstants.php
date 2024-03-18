<?php

namespace App\Constants;

class AppConstants
{
    const APP_1001 = '1001';
    const APP_1002 = '1002';
    const APP_1003 = '1003';
    const APP_1004 = '1004';
    const APP_1005 = '1005';
    const APP_1006 = '1006';
    const APP_1007 = '1007';
    const APP_1008 = '1008';
    const APP_1009 = '1009';
    const APP_1010 = '1010';
    const APP_1011 = '1011';



    const RESPONSE_CODES_MESSAGES = [
        self::APP_1001 => 'translation.1001.message',
        self::APP_1002 => 'translation.1002.message',
        self::APP_1003 => 'translation.1003.message',
        self::APP_1004 => 'translation.1004.message',
        self::APP_1005 => 'translation.1005.message',
        self::APP_1006 => 'translation.1006.message',
        self::APP_1007 => 'translation.1007.message',
        self::APP_1008 => 'translation.1008.message',
        self::APP_1009 => 'translation.1009.message',
        self::APP_1010 => 'translation.1010.message',
        self::APP_1011 => 'translation.1011.message',




    ];

    public function __construct() {
        __('translation.1001.title');
        __('translation.1002.title');
        __('translation.1003.title');
        __('translation.1004.title');
        __('translation.1005.title');
        __('translation.1006.title');
        __('translation.1007.title');
        __('translation.1008.title');
        __('translation.1009.title');
        __('translation.1010.title');
        __('translation.1011.title');





        __('translation.1001.message');
        __('translation.1002.message');
        __('translation.1003.message');
        __('translation.1004.message');
        __('translation.1005.message');
        __('translation.1006.message');
        __('translation.1007.message');
        __('translation.1008.message');
        __('translation.1009.message');
        __('translation.1010.message');
        __('translation.1011.message');



    }
}
