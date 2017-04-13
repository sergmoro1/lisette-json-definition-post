<h1>WordPress plugin for working with JSON definitions in a post content</h1>

<h2>Demo</h2>

<a href='http://lisette.vorst.ru'>Plugin for Real Estate Agencies</a>.

<h2>Purpose</h2>
A huge number of ready themes and the constant emergence of new encourages developers 
to write plugins for tasks not typical for WordPress. Often there are added a new data structures to CMS.

Lisette_JDP does not change anything in the data structure of WordPress. However, it is easy
turns post_content to the record (model), which then works.

The post becomes a record for the end user, with the opportunity to ask 
selection criteria or to prepare feed for transmission in third-party services.

The system administrator also gets the opportunity to work with the post how to record 
editing fields using custom forms.

<h2>How it's made</h2>
The attribute values of records are stored in post_content field in the form of notation like JSON definition. 
The plugin reads the definitions and converts them into records on the fly.

<h2>Installation</h2>
The catalogue includes a plug configured for real estate agencies.
Possible any other setting. How this can be done, see below.

Installation the usual for WordPress - download, unzip, put it in the plugins directory and activate.

<h2>Setting</h2>
Unlike most plug-ins for WordPress plugin Lisette_JDP has no configuration tools
in the administration panel exept two widgets - criteria form and map.

The customizations should be done in the configuration files and forms files. 
That is the way it is done by programmers.

What can be customized?

If you want to change fields of the record

1. Attributes (fields) of records you would like to fill are in the file ./config/new_ad.json.
Format is field_name: value by default.
2. Given in accordance with the modifications to the files ./config/fields.php, ./config/select_options.php, ./config/categories.php.
3. Form ./views/edit.php designed to edit the fields in the administration panel. 
And other views for frontend, is located in the same folder.
4. To change the event handlers to respond to user actions on the client side in the file ./js/start.js.

If you want to change the form of the search criteria

1. Change form ./views/criteria.php used to enter search criteria.
2. The class inherits from LisetteJDPApplication.php. For this plugin is RealtyApplication.php.
You need to override methods, format, condition, yaPoint to bring them into conformity with the new attributes.

