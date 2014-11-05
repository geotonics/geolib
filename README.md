geolib - The geolib php framework
=================================

####What it is

The Geolib PHP Framework is a small, easy to use system for writing webpages. Unlike most frameworks, the focus is on very basic functionality, simplicity, speed, and ease of use. It contains a few simple classes for writing html content, including all form elements, along with some wrappers that make the classes even easier to use.

Also included are a few handy utility functions to perform some of the more common tasks that you will encounter when writing web sites, and an easy to use method for debugging PHP variables.

Here is the [Class Documentation](http://geotonics.com/doc/geolibdoc/).

####What it is not

The Geolib PHP Framework is not an all purpose tool. It can write form elements, but it has no database functions or database interface. However, it can be used along with any database abstraction library, such as The ADOdb Database Abstraction Library, or the Wordpress wpdb object.

Because it doesn't need to get or control data, it doesn't use the Model/View/Controller design pattern, which makes it very easy to use.

####Its Better This Way

You can use the geolib framework to help you write your own database interface, or you can use a separate database library. Either way, you can use geolib to help you write a model/view/controller design pattern to view or control data, or any other design pattern you find convenient. This is actually better than using a framework which insists that you start with a model/view/controller pattern, because the model/view/controller that you write won't have to be a complex monstrousity adabtable to every conceivable situation. It can be specifically targeted to serve your particular website, and will therefore be simpler, more adaptable and easier to use.