<?php

if (!function_exists('getTimeRange')) {
    function getTimeRange($timeOfDay)
    {
        return match ($timeOfDay) {
            'Morning' => '6:00 AM - 12:00 PM',
            'Afternoon' => '12:00 PM - 5:00 PM',
            'Evening' => '5:00 PM - 11:00 PM',
            default => 'N/A',
        };
    }
}
