<?php


namespace Wefabric\TokenLogin\Concerns;


interface TokenGenerateInterface
{
    public function execute($uniqueValue): string;
}
