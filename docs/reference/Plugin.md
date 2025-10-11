Namespace: ARC\Gateway
FQCN: ARC\Gateway\Plugin
Class Name: Plugin
Relative File Path: /Plugin.php 

Standardized main plugin file.

Sets file path and URL constants.

Includes classes from the /includes directory. Should be using SPL autoloader, but does not currently implement one. 

Initializes the admin page. 

It flushes rewrite rules during activation and deactivation. It is not clear if that is necessary or not. 

