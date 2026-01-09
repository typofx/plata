<?php
/**
 * Social Media Configuration
 * Defines all available social media platforms
 */

$socialMedias = [
    'whatsapp' => [
        'label' => 'Whatsapp',
        'maxlength' => '20',
        'placeholder' => '(XX) XXXXX-XXXX'
    ],
    'instagram' => [
        'label' => 'Instagram',
        'maxlength' => '50',
        'placeholder' => '@user'
    ],
    'telegram' => [
        'label' => 'Telegram',
        'maxlength' => '50',
        'placeholder' => '@user'
    ],
    'facebook' => [
        'label' => 'Facebook',
        'maxlength' => '100',
        'placeholder' => 'https://facebook.com/user'
    ],
    'github' => [
        'label' => 'GitHub',
        'maxlength' => '200',
        'placeholder' => 'https://github.com/user'
    ],
    'social_email' => [
        'label' => 'Social Email (Public)',
        'maxlength' => '100',
        'placeholder' => 'contact@email.com',
        'type' => 'email'
    ],
    'twitter' => [
        'label' => 'Twitter (X)',
        'maxlength' => '50',
        'placeholder' => 'user'
    ],
    'linkedin' => [
        'label' => 'LinkedIn',
        'maxlength' => '200',
        'placeholder' => 'https://linkedin.com/in/user'
    ],
    'twitch' => [
        'label' => 'Twitch',
        'maxlength' => '50',
        'placeholder' => 'username'
    ],
    'medium' => [
        'label' => 'Medium',
        'maxlength' => '50',
        'placeholder' => 'username'
    ]
];

// Positions available for team members
$positions = ['INTERN'];
?>
