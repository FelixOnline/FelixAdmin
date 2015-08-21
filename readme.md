## Installation

Proper instructions coming soon.

1. Set up config.php as per config.example.php
2. Set up htaccess. Example format:

```
RewriteEngine on
RewriteBase /admin
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?q=$1 [L,QSA]
```

## Writing page files

Coming soon

## Updating the menu

Coming soon

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