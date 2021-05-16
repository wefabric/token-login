<?php


namespace Wefabric\TokenLogin\Actions;


use Illuminate\Support\Facades\Hash;
use Wefabric\TokenLogin\Concerns\TokenGenerateInterface;

class GenerateTokenAction implements TokenGenerateInterface
{
    /**
     * @param $uniqueValue
     * @return string
     */
    public function execute($uniqueValue): string
    {
        return Hash::make($uniqueValue.time());
    }
}
