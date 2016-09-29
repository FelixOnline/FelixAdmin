## Installation

Proper instructions coming soon.

1. Run composer install
2. Set up config.php as per config.example.php
3. Set up htaccess. Example format:

```
RewriteEngine on
RewriteBase /admin
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?q=$1 [L,QSA]
```

A sample file has been provided.

If you host Admin on the same server as the main site, you may need to define CACHE_PATH in the configuration file and set it to the same path as the main site for cache resetting to work.

## How does this all work?

Details are provided in the Github wiki.

## Writing page files

These go in the "files" folder.

The page file name gives the index used in URLs.

The format of the page file is as follows:

```
{
	"name": // Title shown in menus
	"model": // Class name for the model which must inherit BaseDB in Core
	"baseRole": [] // Array of role names which are required to access the page - a user with ANY of these roles (even if inherited) will be allowed in
	"constraints": [{ // array of objects
		"specialConstraint": // If this is specified, a special constraint will be applied. Valid options are isAuthor (for core Article objects only) or isEditor (for core Article objects - is article in a category the user edits - or core Category objects only) or isMe (for core User objects - is user the logged in user)
		"reverse": // If above is present, make the test negative
		"roles": [] // Array of roles this constraint applies to, if empty/not set applies to all roles - if a user has ANY of these roles it will apply _UNLESS_ the role is inherited
		"notRoles": [] // Array of roles that, if the user has (*even if implicitly*), the constraint will not apply. For example, if you have an isAuthor constraint but put sectionEditor here, section editors will not have to be authors of articles to see them on this page.
		"field": // If specialConstraint NOT specified, what field to test
		"operator": // If above is present, what SQL operator to apply
		"test": // What value to compare to
	}]
	"fields": {
		"fieldName": { // What field in the model this relates to
			"label": // What label to show
			"readOnly": // Field cannot be edited
			"required": // Field must be present to create or save (if its read only, creation may be prevented unless autofield is set)
			"autoField": // If this is set, on record creation, datetime fields will be set to the current datetime, foreign keys pointing to core User objects will be set to the current user, foreign keys pointing to core Image objects will be set to the default image if one is set, and all other autoFields will be blank or have the defaultValue set (if there is one).
			"validation": // If this is set to "email" the value will be checked to ensure it is an email address
			"help": // Help text shown on the interface
			"foreignKeyField": // If this is a foreign key field, what value from the foreign key to show in the UX
			"imageDetailsEditor": // If this is a foreign key pointing to an image object, show an editor to set the image attribution and description (this does not affect captions)
			"canUpload": // If this is a foreign key pointing to an image object, allow people to upload new images
			"canPick": // If this is a foreign key pointing to an image object, allow people to select previously uploaded images
			"defaultImage": // If this is a foreign key pointing to an image object, the ID number for the default image for this field. If set, a button will be provided to set the image to this default image
			"multiMap": { // If this is set, you can select multiple foreign keys for this field
				"model": // Class name for the model which must inherit BaseDB in Core and must map foreign keys to the record relevant to this page. For example, one that maps multiple authors to an article. Or, alternatively, it can point to a table where records point to the record relevant to this page, in which case set foreignKey to the field to fetch from the foreign key table (in raw format), and you will be unable to edit or search on this field (so must set skip to true).
				"this": // The column in the model relating to this page
				"foreignKey": // The column in the model relating to the foreign key
				"foreignKeyField": // What value from the foreign key to show in the UX
			}
			"skip": // If a multiMap, do not show on edit/new screens
			"choiceMap": { // If this is set, you can enter multiple new text items for this field - do not make something both a multiMap and a choiceMap
				"model": // Class name for the model which must inherit BaseDB in Core and must map strings to the record relevant to this page. For example, a table of tag name and article ID
				"this": // The column in the model relating to this page
				"field": // What field from the foreign table to allow editing in
			}
			"defaultValue": // Default value for new entry forms - will NO LONGER be lost when the form resets after succesful creation. Valid only on checkbox, datetime, numeric, string, and foreignkey fields. On datetime fields, please supply a POSIX timestamp.
		}
	}
	"order": [{ // array of objects
		"column": // Column name to order
		"direction": // ASC or DESC
	}]
	"actions": { // Actions to run on selected records in a list view
			"doThis": { // Action name - must be a class in the FelixOnline\Admin\Actions namespace
				"label": "Do something", // Button label
				"icon": "road", // Glyphicon name (see Bootstrap docs)
				"roles": [] // What roles can access this action, if empty/not set applies to all roles - a user with any of the roles in this list (even if inherited) will be allowed
		}
	}
	"modes": {
		"new": {
			"enabled": // Is this tab available
			"roles": [], // What roles can access this table, if empty/not set applies to all roles - a user with any of the roles in this list (even if inherited) will be allowed to access this tab
			"callback": // If specified, the action (see the actions section) will be executed with the new record. Please note that the message from the callback will replace that of the link to the details page for the new entry
		},
		"search": {
			"enabled": // Is this tab available
			"roles": [] // What roles can access this table, if empty/not set applies to all roles - a user with any of the roles in this list (even if inherited) will be allowed to access this tab
			"fields": [] // What fields can be searched. Note that multiMap fields cannot be searched and will be ignored
		},
		"list": {
			"enabled": // Is this tab available
			"roles": [] // What roles can access this table, if empty/not set applies to all roles - a user with any of the roles in this list (even if inherited) will be allowed to access this tab
			"columns": [] // What columns to show in the table. This will also apply to searches. Foreign keys pointing to core Text objects cannot be shown here
			"canDelete": // Can people delete objects, will show a bin icon if true
		},
		"details": {
			"enabled": // Is this tab available
			"roles": [] // What roles can access this table, if empty/not set applies to all roles - a user with any of the roles in this list (even if inherited) will be allowed to access this tab
			"readOnly": // Lock all fields as read only (for forms where you can create entries but not edit)
		},
	}
	"defaultTab": // Which of the above tabs to show by default. Showing details by default may not be hugely beneficial
	"auxHTML": // Some arbitrary HTML to show on the page
}
```

The only required components are:

* Name
* BaseRole

## Updating the menu

The menu is stored in the menu.json file and takes the following format:

```
{
	"pageName": {}, // A page with no children, pageName corresponds to the page file name minus the .json extension
	"secondPageName": { // A second page with children
 		"children": { // Define the children here
			"thirdPageName": {} // A third page, child of the second page, has no children (you can have infinite levels of children)
		}
	}
}
```

While it is possible for a page to exist in multiple places, this may confuse the menuing system as the menu has no knowledge of the hierarchy used to reach the page you requested. Therefore, the page may be shown as belonging under a different parent to the one you originally clicked.

The menu shown on screen is based on this file, with pages the user cannot access (and any children they have) removed. This will be refreshed on every login.

## rebuildArchive.php

Run this script to rebuild issue archive thumbnails and extract contents for the fulltext search.

## Sir Trevor twitter

The Sir Trevor editor will work automatically with Twitter if you create a Twitter app and set the application api key and secret in the Settings table.

## Third-party libraries

The following third-party javascript libraries are used:

* Bootstrap Datetime Picker
  * Moments.js included as a dependancy
* Dropzone.js
* jQuery Tablesorter fork - http://mottie.github.io/tablesorter/docs/index.html
* lodash.js
* Sir Trevor editor

The Sir Trevor editor used is 0.4.3 with *some modifications* to patch bugs and annoyances. Specifically:

* A patch has been applied to stop Sir Trevor scrolling as some articles are very long and hence scrolling is very annoying - https://github.com/madebymany/sir-trevor-js/commit/8696e11e7ee8a97cdbf4efe9d4447266661e61c5 (included in 0.5)
* A patch has been applied to the CSS to set the z-index for the formatting toolbar on .st-format-bar to 10000. This makes sure it appears in Bootstrap modals (included in 0.5)
* A patch has been applied to make editor reinitialisation work. The following code was inserted into the destroy method replacing the similar existing code (included in 0.5)

```
// Destroy all blocks
this.block_manager.blocks.forEach(function(block) {
  this.mediator.trigger('block:remove', block.blockID);
}, this);
```

* Additionally the Cite module has been overridden to stop making the reference source compulsory.

If you wish to upgrade to newer versions of Sir Trevor none of these patches require re-applying (the Cite block one is a separate JS file so no changes to the original source were made). However, newer versions use HTML storage instead of Markdown, which breaks the Felix workflow. A rewrite of the sir-trevor-php plugin in the main site is needed. Alternatively Sir Trevor's developers may hopefully provide future versions with a Markdown plugin.