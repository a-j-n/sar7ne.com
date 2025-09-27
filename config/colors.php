<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Color System Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the color configuration for both light and dark modes.
    | You can easily modify colors here to create new themes or adjust contrast.
    |
    */

    'brand' => [
        'orange' => '#FF6B3D',
        'yellow' => '#FFE066',
        'pink' => '#FF5D8F',
        'blue' => '#3A86FF',
        'mint' => '#2EC4B6',
    ],

    'light_mode' => [
        'bg' => [
            'primary' => '#FFFFFF',      // Canvas
            'secondary' => '#F5F7FB',    // Section backgrounds
            'tertiary' => '#EBF1FF',     // Cards
            'accent' => '#DEE8FF',       // Highlights
        ],

        'text' => [
            'primary' => '#0F172A',      // Headings / body
            'secondary' => '#334155',    // Subtext
            'tertiary' => '#4A607A',     // Muted body
            'muted' => '#7B8AA6',        // Placeholder
        ],

        'border' => [
            'primary' => '#E2E8F0',      // Dividers
            'secondary' => '#CBD5E1',    // Component borders
            'accent' => '#B7C5EB',       // Focus accents
        ],

        'interactive' => [
            'hover' => '#EDF2FF',        // Hover surfaces
            'active' => '#DCE6FF',       // Active states
            'focus' => '#B4C6FF',        // Focus ring
        ],
    ],

    'dark_mode' => [
        'bg' => [
            'primary' => '#0F172A',      // Canvas
            'secondary' => '#1B2537',    // Section backgrounds
            'tertiary' => '#253044',     // Cards
            'accent' => '#32405A',       // Highlights
        ],

        'text' => [
            'primary' => '#F8FAFC',      // Headings / body
            'secondary' => '#D8E2F2',    // Subtext
            'tertiary' => '#AEBED8',     // Muted body
            'muted' => '#7C8BA7',        // Placeholder
        ],

        'border' => [
            'primary' => '#2E3A52',      // Dividers
            'secondary' => '#3B4965',    // Component borders
            'accent' => '#4C5D83',       // Focus accents
        ],

        'interactive' => [
            'hover' => '#1D2940',        // Hover surfaces
            'active' => '#28344E',       // Active states
            'focus' => '#3E4F78',        // Focus ring
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Semantic Colors
    |--------------------------------------------------------------------------
    |
    | Colors for specific UI states that work in both light and dark modes
    |
    */

    'semantic' => [
        'success' => [
            'light' => '#10B981',
            'dark' => '#34D399',
        ],
        'warning' => [
            'light' => '#F59E0B',
            'dark' => '#FBBF24',
        ],
        'error' => [
            'light' => '#EF4444',
            'dark' => '#F87171',
        ],
        'info' => [
            'light' => '#3B82F6',
            'dark' => '#60A5FA',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Gradients
    |--------------------------------------------------------------------------
    |
    | Brand gradients for special elements
    |
    */

    'gradients' => [
        'orange_pink' => 'linear-gradient(90deg, #FF5E1A, #FF4D6D)',
        'blue_mint' => 'linear-gradient(90deg, #2D9CDB, #2EC4B6)',
        'brand_glow' => 'linear-gradient(135deg, #FF5E1A 0%, #FF4D6D 50%, #FFD93D 100%)',
    ],
];
