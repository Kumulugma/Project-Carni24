<?php

class K3eRobots {

    const VERSION = '0.1a';
    const NAME = 'Robots.TXT';
    
    function __construct() {

        add_action('publish_post', 'K3eRobotsTXT');
        add_action('publish_page', 'K3eRobotsTXT');
        add_action('save_post', 'K3eRobotsTXT');

        function K3eRobotsTXT() {
            Robots::generate();
        }

    }

}
