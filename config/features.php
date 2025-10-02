<?php

return [
    // Toggle the interactive map on the Timeline composer
    'timeline_map' => env('FEATURE_TIMELINE_MAP', fasle),

    // Generate image thumbnails for timeline attachments
    'thumbnails' => env('FEATURE_IMAGE_THUMBNAILS', false),
];
