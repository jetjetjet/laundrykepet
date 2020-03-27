<?php
namespace App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use DB;

class Can
{
    private $permissions = [];
    public function __construct(array $perm)
    {
        $this->permissions = $perm;
    }

    public function can($actions)
    {
        $valids = array_unique(array_map(function ($action){
            return in_array($action, self::$permissions, true);
        }, $actions));

        return !in_array(false, $valids, true);
    }
}