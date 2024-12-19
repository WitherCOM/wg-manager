<?php

namespace App\Services;

class Wireguard {

    public function presharedKey(): string|null
    {
        $cmdOutput = null;
        $returnCode = -1;

        # generate preshared key with wg command
        exec("wg genpsk", $cmdOutput, $returnCode);

        if ($returnCode === 0 && array_key_exists(0, $cmdOutput))
        {
            // generation was successful
            return $cmdOutput[0];
        }
        else
        {
            // generation failed
            return null;
        }
    }

    public function privateKey(): string|null
    {
        $cmdOutput = null;
        $returnCode = -1;

        # generate private key with wg command
        exec("wg genkey", $cmdOutput, $returnCode);

        if ($returnCode === 0 && array_key_exists(0, $cmdOutput))
        {
            // generation was successful
            return $cmdOutput[0];
        }
        else
        {
            // generation failed
            return null;
        }
    }

    public function publicKey(string $privateKey): string|null
    {
        $cmdOutput = null;
        $returnCode = -1;

        // ALERT command injection possible $privateKey variable should not contain user input
        $cmd = 'echo "' . $privateKey . '" | wg pubkey';

        # retrieving public key with wg command
        exec($cmd, $cmdOutput, $returnCode);

        if ($returnCode === 0 && array_key_exists(0, $cmdOutput))
        {
            // retrieving was successful
            return $cmdOutput[0];
        }
        else
        {
            // retrieving failed
            return null;
        }
    }

    public function interfaceUp(): bool
    {
        $returnCode = -1;

        # enable wireguard interface
        exec("wg-quick up wg0", return_var: $returnCode);

        return $returnCode === 0;
    }

    public function interfaceDown(): bool
    {
        $returnCode = -1;

        # disable wireguard interface
        exec("wg-quick down wg0", return_var: $returnCode);

        return $returnCode === 0;
    }

    public function setConfig(string $config): bool
    {
        file_put_contents("/etc/wireguard/wg0.conf", $config);
    }
}
