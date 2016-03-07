<?php namespace ColdTurkey\HomesReport;
/*
 * Plugin Name: Homes Report
 * Version: 1.3
 * Plugin URI: http://www.coldturkeygroup.com/
 * Description: WordPress funnel that collects visitor contact information in exchange for a customized report from a Real Estate agent.
 * Author: Cold Turkey Group
 * Author URI: http://www.coldturkeygroup.com/
 * Requires at least: 4.0
 * Tested up to: 4.3
 *
 * @package House Hunter
 * @author Aaron Huisinga
 * @since 1.0.0
 */

if (!defined('ABSPATH')) exit;

if (!defined('HOMES_REPORT_PLUGIN_PATH'))
    define('HOMES_REPORT_PLUGIN_PATH', trailingslashit(plugin_dir_path(__FILE__)));

if (!defined('HOMES_REPORT_PLUGIN_VERSION'))
    define('HOMES_REPORT_PLUGIN_VERSION', '1.3');

require_once('classes/class-homes-report.php');

global $house_hunter;
$house_hunter = new HomesReport(__FILE__, new FrontDesk());

if (is_admin()) {
    require_once('classes/class-homes-report-admin.php');
    new HomesReport_Admin(__FILE__);
}
