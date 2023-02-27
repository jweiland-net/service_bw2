..  include:: /Includes.rst.txt


..  _typoscript:

==========
TypoScript
==========

`service_bw2` needs some basic TypoScript configuration. To do so you have to add an +ext template to either the root
page of your website or to a specific page which contains the `service_bw2` plugin.

..  rst-class:: bignums

1.  Locate page

    You have to decide where you want to insert the TypoScript template. Eithe root page or page with `service_bw2`
    plugin is OK.

2.  Create TypoScript template

    Switch to template module and choose the specific page from above in the pagetree. Choose
    `Click here to create an extension template` from the right frame. In the TYPO3 community it is also known as
    "+ext template".

3.  Add static template

    Choose `Info/Modify` from the upper selectbox and then click on `Edit the whole template record` button below
    the little table. On tab `Includes` locate the section `Include static (from extension)`. Use the search below
    `Available items` to search for `service_bw2`. Hopefully just one record is visible below. Choose it, to move that
    record to the left.

4.  Save

    If you want you can give that template a name on tab "General", save and close it.

5.  Constants Editor

    Choose `Constant Editor` from the upper selectbox.

6.  `service_bw2` constants

    Choose `PLUGIN.TX_SERVICEBW2` from the category selectbox to show just `service_bw2` related constants

7.  Configure constants

    Adapt the constants to your needs.

8.  Configure TypoScript

    As constants will only allow modifiying a fixed selection of TypoScript you also switch to `Info/Modify` again
    and click on `Setup`. Here you have the possibility to configure all `service_bw2` related configuration.

View
====

view.templateRootPaths
----------------------

Default: Value from Constants *EXT:service_bw2/Resources/Private/Templates/*

You can override our Templates with your own SitePackage extension. We prefer to change this value in TS Constants.

view.partialRootPaths
---------------------

Default: Value from Constants *EXT:service_bw2/Resources/Private/Partials/*

You can override our Partials with your own SitePackage extension. We prefer to change this value in TS Constants.

view.layoutsRootPaths
---------------------

Default: Value from Constants *EXT:service_bw2/Resources/Layouts/Templates/*

You can override our Layouts with your own SitePackage extension. We prefer to change this value in TS Constants.


Settings
========

settings.overridePageTitle
--------------------------

Default: 0 (false)

Set to 1 (true) to override the page title in show actions with item name.

settings.organisationseinheiten.pidOfListPage
---------------------------------------------

Default: <empty>

If you need a link in a detail view to go back into list view please fill that value with a page UID
where the plugin for Organisationseinheiten resides.

settings.organisationseinheiten.pidOfDetailPage
-----------------------------------------------

Default: <empty>

For design resons it may make sense to link an Organisationseinheit onto its own page UID.

settings.leistungen.pidOfListPage
---------------------------------

Default: <empty>

If you need a link in a detail view to go back into list view please fill that value with a page UID
where the plugin for Leistungen resides.

settings.leistungen.pidOfDetailPage
-----------------------------------

Default: <empty>

For design resons it may make sense to link a Leistung onto its own page UID.

settings.lebenslagen.pidOfListPage
----------------------------------

Default: <empty>

If you need a link in a detail view to go back into list view please fill that value with a page UID
where the plugin for Lebenslagen resides.

settings.lebenslagen.pidOfDetailPage
------------------------------------

Default: <empty>

For design resons it may make sense to link a Lebenslage onto its own page UID.

=====
Maps2
=====

If not already done then you need to configure maps2, because `service_bw2` has a `maps2` integration for
departments. Take a look into the `maps2` documentation for that.
