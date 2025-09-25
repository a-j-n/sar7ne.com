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
        'orange' => '#FF5E1A',
        'yellow' => '#FFD93D', 
        'pink' => '#FF4D6D',
        'blue' => '#2D9CDB',
        'mint' => '#2EC4B6',
    ],

    'light_mode' => [
        // Backgrounds - Light and airy
        'bg' => [
            'primary' => '#FFFFFF',      // Pure white for main content
            'secondary' => '#F8FAFC',    // Very light gray for subtle sections
            'tertiary' => '#F1F5F9',     // Light gray for cards/containers
            'accent' => '#EFF6FF',       // Light blue tint for special areas
        ],

        // Text colors - Dark for good contrast on light backgrounds
        'text' => [
            'primary' => '#0F172A',      // Very dark for main text
            'secondary' => '#334155',    // Medium dark for secondary text
            'tertiary' => '#64748B',     // Gray for less important text
            'muted' => '#94A3B8',        // Light gray for placeholders
        ],

        // Borders - Subtle but visible
        'border' => [
            'primary' => '#E2E8F0',      // Light gray borders
            'secondary' => '#CBD5E1',    // Slightly darker for emphasis
            'accent' => '#DBEAFE',       // Light blue for special borders
        ],

        // Interactive elements
        'interactive' => [
            'hover' => '#F1F5F9',        // Light hover state
            'active' => '#E2E8F0',       // Pressed state
            'focus' => '#DBEAFE',        // Focus ring background
        ],
    ],

    'dark_mode' => [
        // Backgrounds - True dark theme
        'bg' => [
            'primary' => '#0F172A',      // Very dark blue-gray for main content
            'secondary' => '#1E293B',    // Slightly lighter for sections
            'tertiary' => '#334155',     // Medium dark for cards/containers
            'accent' => '#1E3A8A',       // Dark blue for special areas
        ],

        // Text colors - Light for good contrast on dark backgrounds
        'text' => [
            'primary' => '#F8FAFC',      // Very light for main text
            'secondary' => '#E2E8F0',    // Light gray for secondary text
            'tertiary' => '#CBD5E1',     // Medium gray for less important text
            'muted' => '#94A3B8',        // Darker gray for placeholders
        ],

        // Borders - Visible but not harsh
        'border' => [
            'primary' => '#475569',      // Medium gray borders
            'secondary' => '#64748B',    // Lighter for emphasis
            'accent' => '#3B82F6',       // Blue accent borders
        ],

        // Interactive elements
        'interactive' => [
            'hover' => '#374151',        // Lighter hover state
            'active' => '#4B5563',       // Pressed state
            'focus' => '#1E3A8A',        // Focus ring background
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
