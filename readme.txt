=== Visitor Country ===
Contributors: Izhaki
Tags: geo, location, visitor, country, analysis
Requires at least: 3.2
Tested up to: 3.5.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin that retrieves the visitor's country information (using MaxMind's GeoIP local dat file)

== Description ==

This plugin adds support for displaying (using shortcodes) or enquiring (using PHP or JS) the visitor's country. See it working [here](http://gefri.org/misc/wordpress/visitor-country).

It uses [MaxMind's GeoIP data file](http://www.maxmind.com/app/geolitecountry), which is around 1.4 MB in size and is included with the plugin. The file is free, updated every month, and has the claimed accuracy of 99.5%;

GitHub Repository is [here](https://github.com/Izhaki/Visitor-Country). 

The plugin retrieves:

* The visitor's IP
* The visitor's country name
* The visitor's country code (2 letter ISO 3166)

The shortcodes are:

* `[VisitorCountry-IP]`
* `[VisitorCountry-Code]`
* `[VisitorCountry-Name]`

The javascript variables are:

* `VisitorCountry.ip`
* `VisitorCountry.code`
* `VisitorCountry.name`

And the PHP functions:

    global $VisitorCountry;
    if ( isset($VisitorCountry) )
    {
        echo $VisitorCountry->GetIP();    
        echo $VisitorCountry->GetCode();
        echo $VisitorCountry->GetName();        
    }

Please leave suggestions, comments and bug report at [the plugin's website](http://gefri.org/misc/wordpress/visitor-country).

Also, please let it be known if the plugin works on versions older than 3.2 - I simply didn't have a way to check.

----
This product includes GeoLite data created by MaxMind, available from http://www.maxmind.com/app/geolitecountry

== Installation ==

1. Upload the `visitor-country` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Is there any database to install? =

No.

= Does the plugin access an external site? =

No. It uses local database (dat file).

= Can the plugin retrieve the city of the visitor, what about latitude and longtitude information? =

No. The accuracy of [MaxMind's GeoLite City database](http://www.maxmind.com/app/geolitecity) is far from perfect; combined with a much bigger database, there's no much point supporting it in this plugin. Shall there be such a demand, a separate city plugin will be written.

= Does the plugin support IP6 addresses? =

Not at the moment, but this will be implemented shall there be a demand.

= How do I update the database? =

The plugin will go up in version periodically to reflect MaxMind's new database. You can always update it manually by [downloading it from here](http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz), uncompress, and upload to the plugin directory.

== Changelog ==

= 1.1 =

Fixed Proxy issue
GeoIP DB updated to May 2013

= 1.0 =
* MaxMind database update
* Improvement to ip detection to include intranet and proxy (Thanks Ade!)

= 0.8 =
* Original release