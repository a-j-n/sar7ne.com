<?php

return [
    // Timeline feature removed

    // Generate image thumbnails for timeline attachments
    'thumbnails' => env('FEATURE_IMAGE_THUMBNAILS', false),

    // UI timings (ms)
    'toast_timeout' => env('UI_TOAST_TIMEOUT', 3500),
    'confirm_toast_close_ms' => env('UI_CONFIRM_TOAST_CLOSE_MS', 180),
    'sheet_duration' => env('UI_SHEET_DURATION', 300),
    'fab_idle_timeout' => env('UI_FAB_IDLE_TIMEOUT', 1500),
    'fab_initial_reveal' => env('UI_FAB_INITIAL_REVEAL', 800),
];
