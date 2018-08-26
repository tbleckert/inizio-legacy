# Welcome to Inizio #

*No longer maintained and shouldn't be used. Keeping it for the fun of it 🌝*

Inizio is a wordpress theme starting point. It was originally built by [eddiemachado](https://github.com/eddiemachado/bones).
He did a great job with Bones, but I wanted it more stripped out, I wanted just the bones, no flesh.

![Logo](https://dl.dropboxusercontent.com/u/6306766/inizio.png)

## Screenshots

![Screenshot](https://dl.dropboxusercontent.com/u/6306766/inizio_login.png)

## Features

* Shortcuts for many Wordpress functions
* Easy page specific assets
* Pagination helper
* Handler to remove items from the admin menu
* Limit text by words
* Handler to hide stuff from the sidebar
* Facebook-like time (1 minute ago)
* Widget helper to make it easier to create widgets
* Custom post type helper
* Language link manager
* And lots of other improvements like cleaner code, sass support, custom login etc...

## Get started ##

I've provided you with some example files, be sure to check them out and read the comments. So let me walk you through the theme structure:

### Assets ###

* assets/
  * css/
    * gfx/
  * js/
    * libs/
  * img/
* admin/
	* css/
		* gfx/
	* js/
	* img/
    
All sorts of assets are located in the __assets__ folder, those are __css__ and __js__ files and __images__.
I always uses a sub-folder in the __css__ directory called __gfx__. In this folder I store design related images, images that is used via __background-image__.
The reason I do this is to separate content images and design images. Content images should be in the __img__ folder.
Last but not least we have the __js__ files, no need to tell you where they should go. Javascript libraries goes into the __libs__ folder.

Note that you don't have to use this structure if you don't feel comfortable with it, feel free to setup your own, maybe also share your thoughts on this topics too.

### Helpers ##

I call these helpers because they're not template files, these are files that provides extra functionality.
When you first look into this folder you will see that there's already five files in there. The most important are __inizio.php__ and __initial.php__.

#### inizio.php and initial.php ####
inizio.php is the core class. It is a collection of useful functions for you to use when you develop your theme. I will not go through all of them but check out __functions.php__, there's some examples and everything is well commented in the inizio.php file too.
initial.php is extended by inizio.php and is a collection of functions that cleans up wordpress a bit. These will autorun when you initiate Inizio.

#### The other files ####
I will make this quick. The other files is a custom post type helper with an example of how to use it and the other one is a widget helper. Check those files out and you'll learn more.

## Useful plugins ##

Some stuff is more suitable to be written as a plugin. There's lots of plugins out there but not all of them are well written.
Therefor you should always be careful when installing a new plugin, it may make your site slower and/or causing other problems.

Enough said on that, here's a list of useful and well written plugins that I strongly recommend. I'm not going to say something about them, but believe me, it's worth checking them out.

* [Debug Bar](http://wordpress.org/extend/plugins/debug-bar/) – "A must have when developing"
* [WordPress SEO by Yoast](http://wordpress.org/extend/plugins/wordpress-seo/) – "Improve your WordPress SEO: Write better content and have a fully optimized WordPress site using the WordPress SEO plugin by Yoast."
* [W3 Total Cache](http://wordpress.org/extend/plugins/w3-total-cache/) – "Improve site performance and user experience via caching: browser, page, object, database, minify and content delivery network support."
* [Breadcrumb NavXT](http://wordpress.org/extend/plugins/breadcrumb-navxt/) – "Adds breadcrumb navigation showing the visitor's path to their current location."
