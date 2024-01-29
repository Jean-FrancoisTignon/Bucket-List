<?php

namespace App\Service;

class Censurator
{
    const motsInterdits = ["fuck", "connard", "bullshit", "beuh"];
    public function purify($string): string
    {
        foreach (self::motsInterdits as $motsInterdit)
        {
            $remplacement = str_repeat('*',mb_strlen($motsInterdit));
            $string = str_replace($motsInterdit, $remplacement, $string);
        }

        return $string;
    }
}