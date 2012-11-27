<?php

$patterns = array(
    '+7 917 3185266'                    => '89173185266',
    '+7 (917) 3185266'                  => '89173185266',
    '+7-917-318-5266'                   => '89173185266',
    '89173185266'                       => '89173185266',
    '9173185266'                        => '89173185266',
    '3185266'                           => '84953185266',

    '+7 (917) 3185266 доб 318'          => '89173185266',
    '+7 (917) 3185266 +7 917 318 52 66' => '89173185266',

    '79173185266 звонить после 19'      => '89173185266',

    '123456789012'                      => '',
    '12345678901'                       => '',
    '123456789'                         => '',
    '12345678'                          => '',
    '12345678 звонить после 19'         => '',
    '123456789 звонить после 19'        => '',

    '1234567890'                        => '81234567890',
    '1234567890 звонить после 19'       => '81234567890',

    '3185266 и 318 52 66'               => '84953185266;84953185266',
    '3185266 и +7 917 318-52-66'        => '84953185266;89173185266',

    '+7(927) 134 40 22'                 => '89271344022',
    '+7(927)1344022'                    => '89271344022',
    '+7(927)134 40 22'                  => '89271344022',
    '+7(927) 134 4022'                  => '89271344022',
    '+79271344022'                      => '89271344022',
    '8  927 134 40 22'                  => '89271344022',
    '89271344022'                       => '89271344022',
    'сот +7(927)1344022'                => '89271344022',
    'дом:+7(8453)551720; сот:+79271344022'  => '88453551720;89271344022',
    'сот89271344022,дом88453551720'     => '89271344022;88453551720',
    'дом 8(8453)551720, сот 89271344022'=> '88453551720;89271344022',

    'дом(8453)551720сот9271344022'      => '88453551720;89271344022',
    'дом(8453)551720 СОТ 9271344022'    => '88453551720;89271344022',
);

$result = array();

foreach ($patterns as $phone => $expect){

	$result = phone_find($phone);

    if ( $result !== $expect){
        $result = var_export($result, true);
        echo "Not equal:\ninput:  $phone\nresult: $result\nexpect: $expect\n\n";
    }
}

/**
 * Find one or more phones in text
 *
 * @param string $text
 * @return string phone phone numbers, separated by a comma
 */
function phone_find($text){
    $list   = preg_split('/[а-яa-z;,\:]/ui', $text);

    $result = '';
    foreach ($list as $value){
        $result = rtrim($result, ';');
        $result .= ';' . phone_normalization($value);
    }
    $result = trim($result, ';');
	return $result;
}

/**
 * Cause the phone number to the format 8xxxxxxxxx
 *
 * @param string $phone
 * @return string|boolean phone number or false
 */
function phone_normalization($phone, $area = '495'){

    $phone = preg_replace('/[^\d\w\+]/u', '', $phone);
    $phone = preg_replace('/^\+?7|8(\d{10})/', '8\\1', $phone);

    if (preg_match('/^\d{6,}/u', $phone, $match) == false){
        return false;
    }
    $phone = $match[0];

    if (strlen($phone) === 10 - strlen($area)){
        $phone = $area . $phone; 
    }

    if (strlen($phone) === 10){
        $phone = '8' . $phone;
    }

    if (preg_match('/^(8\d{10})[^\d]?/', $phone, $match) === 1){
        return $match[1];
    }

    return false;
}