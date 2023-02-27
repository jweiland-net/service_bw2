..  include:: /Includes.rst.txt


..  _extensionConfiguration:

=======================
Extension Configuration
=======================

You need to edit the extension configuration after the installation.
Open the extension manager, search for `service_bw2` and click on the settings-icon.

..  figure:: ../../Images/AdministratorManual/OpenExtensionConfiguration.jpg
    :alt: Open extension configuration

Tab: Basic
==========

Username
--------

Default: <empty>

The username provided by Service BW for API usage. (Mostly starts with `ws_`)

Password
--------

Default: <empty>

The password provided by Service BW for API usage.

Mandant
-------

Default: <empty>

The mandant number of the city.

Base URL
--------

Default: https://sgw.service-bw.de:443/

The URL where we can access the API of Service BW.

Allowed languages
-----------------

Default: de=0;en=0;fr=0

This is important if your website is multi language. Format: [2 letters language ISO code]=[sys_language_uid].
Assign multiple languages with ";". Example: de=2;en=5. First value will be used as default language.

AGS
---

Default: <empty>

Description from Service BW API - Die amtlichen Gemeindeschlüssel, in deren Kontext man diese Operation
ausführen möchte. Optional parameter for API requests. Leave empty to not use this filter option.

Gebiet ID
---------

Default: <empty>

Description from Service BW API - Die IDs der Gebiete, in deren Kontext man diese Operation ausführen möchte.
Optional parameter for API requests. Leave empty to not use this filter option.
