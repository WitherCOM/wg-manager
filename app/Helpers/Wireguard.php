<?php

namespace App\Helpers;

class Wireguard
{
    public static function genPresharedKey()
    {
        $output = null;
        $key = null;
        $code = -1;
        exec("wg genpsk", $output, $code);
        if ($code === 0 && array_key_exists(0,$output))
        {
            $key = $output[0];
        }
        return $key;
    }

    public static function genPrivateKey()
    {
        $output = null;
        $key = null;
        $code = -1;
        exec("wg genkey", $output, $code);
        if ($code === 0 && array_key_exists(0,$output))
        {
            $key = $output[0];
        }
        return $key;
    }

    public static function getPublicKey($private_key)
    {
        $output = null;
        $key = null;
        $code = -1;
        $command = 'echo "'.$private_key.'" | wg pubkey';
        exec($command, $output, $code);
        if ($code === 0 && array_key_exists(0,$output))
        {
            $key = $output[0];
        }
        return $key;
    }
}
