<?php

/**
 *
 * Setup Routes here
 *
 * @domain - required
 * @path - required
 * @controller - required
 *
 * @name - optional
 * @method - optional - default: get
 * @middleware - optional - default: web
 *
 */

return [

    [
        'id' => 1,
        'routes' => [
            // Home
            [
                'path' => '/',
                'controller' => 'CampaignController_1@show',
                'name' => 'campaign_1.home',
            ],

            // Web Pages
            [
                'path' => '/{page}',
                'controller' => 'CampaignController_1@pages',
                'name' => 'campaign_1.pages',
            ],

            // Win Page
            [
                'path' => '/t/{tinyurl}',
                'controller' => 'CampaignController_1@tinyurl',
                'name' => 'campaign_1.tinyurl',
            ],

            // Win Page
            [
                'path' => '/win/{id}',
                'controller' => 'CampaignController_1@win',
                'name' => 'campaign_1.win',
            ],

            // Win Submit
            [
                'path' => '/win/{id}',
                'controller' => 'SubmissionController_1@redeem',
                'name' => 'campaign_1.redeem',
                'method' => 'post',
            ],

            // Form Submissions
            [
                'path' => '/submit/{id}',
                'controller' => 'SubmissionController_1@store',
                'name' => 'campaign_1.submissions.store',
                'method' => 'post',
            ],

            // Contact Us Form Submissions
            [
                'path' => '/contact-us',
                'controller' => 'CampaignController_1@contactus',
                'name' => 'campaign_1.contactus.submit',
                'method' => 'post',
            ],
        ],
    ],

];
