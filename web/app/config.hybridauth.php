<?php

/**
 * HybridAuth
 * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
 * (c) 2009-2015, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
 */
// ----------------------------------------------------------------------------------------
// HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

/*

Facebook app:
https://developers.facebook.com/apps/381323445386729/dashboard/

google app:
https://console.developers.google.com/project/thats-the-spirit/apiui/credential

twitter app:
https://apps.twitter.com/app/8399745

Github app:
https://github.com/settings/applications/208551

Reddit app:
https://www.reddit.com/prefs/apps/

 */
return array(
    "callback" => WWWROOT . "/auth/callback",
    "path_vendor" => 'vendor',
    "providers" => array(
        "Google" => array(
            "enabled" => true,
            "keys" => array(
                "id" => '673306183223-0ft3a8jd9icav8l0ida4rhtg0vn847g3.apps.googleusercontent.com',
                "secret" => 'ArQphOkW_M93XKWi0gWL6uRX'
            ),
            "access_type" => "online",
            "scope" => 'https://www.googleapis.com/auth/userinfo.email',
        ),
        "Facebook" => array(
            "enabled" => true,
            "keys" => array("id" => "537179420533589", "secret" => "78d5cd5aec026ee63e1deaa8d888b842"),
            "scope" => "email",
            "trustForwarded" => false
        ),
        "Reddit" => array(
            "enabled" => true,
            "keys" => array("id" => "aSr9-PBrMgpBWA", "secret" => "bpDpDGzPOYnEHIwH_vkjV92-BQI"),
            "scope" => "identity",
            "trustForwarded" => false,
        ),

        "Twitter" => array(
            "enabled" => true,
            "keys" => array(
                "key" => "4GPxkkexLCBntwO9Lq879H9SD",
                "secret" => "flkYZYLTJLEo5nofHj98ClejMqjXeiFJZ1wuHOgxSKq08yfMIa"
            ),
            "includeEmail" => true,
        ),
        'GitHub' => array(
            "enabled" => true,
            "scope" => "user:email",
            "keys" => ["id" => "44ccee421e44fbee93ce", "secret" => "d577497f887b5002e146ef03c14636b7113967b3"]
        ),
    ),
    "debug_mode" => false,
    "debug_file" => "",
);
