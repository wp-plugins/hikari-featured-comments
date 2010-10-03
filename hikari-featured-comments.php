<?php
/*
Plugin Name: Hikari Featured Comments
Plugin URI: http://Hikari.ws/
Description: Adds 3 new custom fields to comments, allowing you to add special properties to each of them.
Version: 0.02.00
Author: Hikari
Author URI: http://Hikari.ws
*/

/**!
*
* I, Hikari, from http://Hikari.WS , and the original author of the Wordpress plugin named
* Hikari Featured Comments, please keep this license terms and credit me if you redistribute the plugin
*
*
*
*   This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*
/*****************************************************************************
* © Copyright Hikari (http://wordpress.Hikari.ws), 2010
* If you want to redistribute this script, please leave a link to
* http://hikari.WS
*
* Parts of this code are provided or based on ideas and/or code written by others
* Translations to different languages are provided by users of this script
* IMPORTANT CONTRIBUTIONS TO THIS SCRIPT (listed in alphabetical order):
*
* Utkarsh Kukreti @ http://wpprogrammer.com/feature-comments-wordpress-plugin/
*
* Please send a message to the address specified on the page of the script, for credits
*
* Other contributors' (nick)names may be provided in the header of (or inside) the functions
* SPECIAL THANKS to all contributors and translators of this script !
*****************************************************************************/

define('HkFC_basename',plugin_basename(__FILE__));
define('HkFC_pluginfile',__FILE__);


require_once 'hikari-tools.php';
//require_once 'hikari-featured-comments-options.php';
require_once 'hikari-featured-comments-core.php';

