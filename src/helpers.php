<?php


if(!function_exists('tokenLogin')) {
    function tokenLogin() : \Wefabric\TokenLogin\TokenLogin
    {
        return \Wefabric\TokenLogin\TokenLogin::make();
    }
}
