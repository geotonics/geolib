The Geolib PHP framework
=================================

####What it is

The Geolib PHP Framework is a small, easy to use system for writing webpages. Unlike most frameworks, the focus is on basic functionality, simplicity, speed, and ease of use. It contains some simple classes for writing html content, including all form elements, along with some wrappers that make the classes very easy to use.

Also included are a few handy utility functions to perform some of the more common tasks that you will encounter when writing web sites, and a method for debugging PHP variables.

Here is the [ApiGen Documentation](http://geotonics.com/doc/geolib/).

####What it is not

The Geolib PHP Framework is not an all purpose tool. It can write form elements, but it has no database functions or database interface. However, it can be used along with any database abstraction library, such as The ADOdb Database Abstraction Library, or the Wordpress wpdb object.

Because it doesn't need to get or control data, it doesn't use the Model/View/Controller design pattern, which makes it very easy to use.

####Its Better This Way

You can use the geolib framework to help you write your own database interface, or you can use a separate database library. Either way, you can use geolib to help you write a model/view/controller design pattern to view or control data, or any other design pattern you find convenient. This is actually better than using a framework which insists that you start with a model/view/controller pattern, because the model/view/controller that you write won't have to be a complex monstrousity adabtable to every conceivable situation. It can be specifically targeted to serve your particular website, and will therefore be simpler, more adaptable and easier to use.

####How to install Geolib

Geolib consists of the /geolib/ directory, which you can add via git or ftp. I usually add the /geolib/ directory to the website's main directory, but it really doesn't matter where you put it. 

To activate Geolib, simply include /geolib/geolib.php
 
To include Geolib automatically in all your web pages, add this to an .htaccess file in the main directory. 

php_value       auto_prepend_file    /path/to/home/geolib/geolib.php

Geolib can also be installed in Wordpress as a Wordpress plugin. 
Download geolib-master.zip file with the "Download" button on
https://github.com/geotonics/geolib

Decompress geolib-master.zip and upload it via FTP to the /wp-content/plugins/ directory, or upload geolib-master.zip via WordPress's dashboard : Plugins > Add New

Activate the plugin through the 'Plugins' menu in WordPress.
You will then see a link to the Geolib settings in the settings menu. 


####Some Optional Settings. 
Once Geolib is installed, open /geolib/defaultConfig.php, save it as config.php, and define various constants that you may find usefull.

####How to debug. 
Geolib includes a debugging system which can be inegrated into your webpages.

To start debugging, go to /geolib/ and click on the links. The debugging password is not required, but if you want to set a password, you can set it in the config.php file. 
To add a variable to the debugging array, use function geodb($value,$name); Once debugging is turned on, all debug variables are automatically dumped at the end of every web page produced by geoHtml(). You can also dump the debug variables anywhere with geoDebug::vars();