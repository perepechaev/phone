<?php

$patterns = array(
    '+7 917 3185266',
    '+7 (917) 3185266',
    '+7-917-318-5266',
    '89173185266',
    '9173185266',
    '3185266',
    '+7 (917) 3185266 доб 318',
    '+7 (917) 3185266 +7 917 318 52 66',
    '123456789012',
);

$result = array();

foreach ($patterns as &$pattern){
    $phone = normalization($pattern);
    if ($phone === false){
        // save original user input
        // and mark dirty it 
        $phone = $pattern . " | DIRTY";
    }

    // save phone in database
    $result[] = $phone;
}

print_r($result);

/**
 * Cause the phone number to the format +7xxxxxxxxx
 *
 * @param string $phone
 * @return string|boolean phone number or false
 */
function normalization($phone, $area = '495'){

    // remove non-digital symbol
    $phone = preg_replace('/[^\d]/', '', $phone);

    $phone = preg_replace('/^[78](\d{10})$/', '+7\\1', $phone);

    if (strlen($phone) === 10 - strlen($area)){
        $phone = $area . $phone; 
    }

    if (strlen($phone) === 10){
        $phone = '+7' . $phone;
    }

    if (preg_match('/^\+7\d{10}$/', $phone) === 1){
        return $phone;
    }

    return false;
}