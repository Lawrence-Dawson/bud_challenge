<?php

namespace App\Services;

class TranslatorService
{
    public function droidToBasic(string $droid)
    {
        $droideries = explode(' ', $droid);
        $string = null;
        foreach ($droideries as $droid) {
            $string .= pack('H*', dechex(bindec($droid)));
        }
 
        return $string;   
    }
}