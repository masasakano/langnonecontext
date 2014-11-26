
The Langnonecontext module provides a context whether a chosen node is
language-neutral (LANGUAGE_NONE) or not, which is used by the Context module.

Install
-------
Basically, follow the standard procedure.
Make sure the Context module is installed and enabled beforehand.

Place everything in this directory into your user module directory, usually:
    /YOUR_DRUPAL_ROOT/sites/all/modules/
preserving the directory structure.
Make sure to rename the filenames so the suffix '.php' is deleted.
For example:
    langnonecontext.module.php => langnonecontext.module

Then enable the module via /admin/modules or drush
    % drush en langnonecontext
Note context and (built-in) locale modules must be enabled beforehand.

Usage
-----
Once installed and enabled, the "Language Neutral Node" Radio-box option is
available in the "Conditions" section in editing the context
in the Context module: /admin/structure/context

Known issues
------------
None.

Acknowledgements 
----------------
The descriptions in the following URIs have been most helpful (Thank you!):
 - https://ohthehugemanatee.org/blog/2013/12/02/custom-context-conditions/
 - http://www.phase2technology.com/blog/the-joy-of-extending-context/
 - http://dtek.net/blog/extending-drupals-context-module-custom-condition-based-field-value

Authors
-------
Masa Sakano - http://www.drupal.org/user/3022767

