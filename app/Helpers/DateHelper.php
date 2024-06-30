<?php 

namespace App\Helpers;

use Carbon\Carbon;

if (!function_exists('formatDate')) {

    function formatDate($date)
    {
        return Carbon::parse($date)->diffForHumans();
    }

}