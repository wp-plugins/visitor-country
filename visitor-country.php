<?php
/*
Plugin Name: Visitor Country
Plugin URI: http://gefri.org/misc/wordpress/visitor-country
Description: A plugin that retrieves the visitor's country information (using MaxMind's GeoIP local dat file)
Version: 1.1
Author: Izhaki
License: GPLv2 or later

    Copyright 2012 Izhaki  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'VisitorCountry' ) ) :

class VisitorCountry {
    public $mPluginDir = '';
    public $mPluginUrl = '';

    public $mIP   = '127.0.0.1';
    public $mCode = '??';
    public $mName = 'Somewhere in the world';

    // constructor (neu school)
    function VisitorCountry() {
        $this->__construct();
    }
    
    // constructor (old school)
    public function __construct()
    {
        $this->LoadVisitorData();
        $this->SetupActions();
        $this->AddShortcodes();    
    }
    
    // Loads the visitor data
    function LoadVisitorData()
    {
        // Get the path of the plugin
        $this->mPluginDir = plugin_dir_path( __FILE__ );
        $this->mPluginUrl = plugin_dir_url ( __FILE__ );
                
        // Include MaxMind's API
        include( $this->mPluginDir . 'geoip.inc');
    
        // Get the visitor IP
        $iIp = $_SERVER[ 'REMOTE_ADDR' ];
        if ( !empty( $_SERVER[ 'HTTP_CLIENT_IP' ] ) ) 
        {               
            // check ip from share internet
            $iIp = $_SERVER[ 'HTTP_CLIENT_IP' ];
        } elseif ( !empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR'] ) )
        {
            // to check ip is pass from proxy
            $iIp = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
        }
        $iIpList = explode(",", $iIp);
        
        // Go through the list of IPs
        foreach( $iIpList as $ip )
        {
            // Ignore LAN IPs and pick the first one representing WAN IPs
            if( substr( $ip,0,8 ) !== '192.168.' )
            {
                $this->mIP=$ip;
                break;
            }
        }
    
        // Connect to MaxMind's GeoIP
        $iGeoIP = geoip_open( $this->mPluginDir . 'GeoIP.dat', GEOIP_STANDARD);
        
        // Get the country id
        $iCountryID = geoip_country_id_by_addr( $iGeoIP, $this->mIP );
        
        if ( $iCountryID !== false ) {
            // Lookup country code and name
            $this->mCode = $iGeoIP->GEOIP_COUNTRY_CODES[$iCountryID];
            $this->mName = $iGeoIP->GEOIP_COUNTRY_NAMES[$iCountryID];            
        }
                
        // Close MaxMind's connection
        geoip_close($iGeoIP);
    }
    
    function SetupActions()
    {
        // Add javascript vars once the head is rendered
        add_action('wp_head', array($this, 'addJavaScriptVars'), 100);
    }
    
    function addJavaScriptVars()
    {
        // We'll be using wp_localize_script to properly add CDATA block. Somewhat oddly we need to load a js script
        // before we can call it, so just load the currently blank visitor-country.js script.
        wp_enqueue_script( 'VisitorCountry', $this->mPluginUrl . 'visitor-country.js');        
        wp_localize_script( 'VisitorCountry', 'VisitorCountry', array(
            'ip'   => $this->GetIP(),
            'code' => $this->GetCode(),
            'name' => $this->GetName()
        ));        
    }
    
    function AddShortcodes()
    {
        // Now lets add some shortcodes
        add_shortcode( 'VisitorCountry-Code', array(&$this, 'GetCode') );
        add_shortcode( 'VisitorCountry-Name', array(&$this, 'GetName') );
        add_shortcode( 'VisitorCountry-IP', array(&$this, 'GetIP') );      
    }

    // PHP getters
    
    function GetIP() {
        return $this->mIP;
    }
    
    function GetCode() {
        return $this->mCode;
    }
    
    function GetName() {
        return $this->mName;
    }       
}

// After all the hard work, time to create our object as a global variable
$GLOBALS['VisitorCountry'] = new VisitorCountry();


endif; // class_exists check

?>