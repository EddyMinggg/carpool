<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Fast Mail Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for optimizing email delivery speed
    |
    */

    // Force sync queue for critical emails like verification
    'force_sync_for_verification' => env('MAIL_FORCE_SYNC_VERIFICATION', true),
    
    // Timeout settings for faster email delivery
    'smtp_timeout' => env('MAIL_SMTP_TIMEOUT', 10),
    
    // Connection pooling for better performance
    'connection_pool_size' => env('MAIL_CONNECTION_POOL_SIZE', 5),
    
    // Skip certain validations for development
    'skip_tls_verification' => env('MAIL_SKIP_TLS_VERIFICATION', false),
];
