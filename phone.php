<?php

/**
 * Find one or more phones in text
 *
 * @param string $text
 * @return string phone phone numbers, separated by a comma
 */
function phone_find($text){
    $list   = preg_split('/[а-яa-z;,\:\+\/]/ui', $text);

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