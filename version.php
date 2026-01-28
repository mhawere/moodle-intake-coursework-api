<?php
defined('MOODLE_INTERNAL') || die();

$plugin->component = 'local_courseworkapi';
$plugin->version = 2024121400;
$plugin->requires = 2022041900; // Moodle 4.0 minimum
$plugin->supported = [400, 500]; // Supports Moodle 4.0 to 5.0
$plugin->maturity = MATURITY_STABLE;
$plugin->release = '2.0.0';
$plugin->cron = 0;
