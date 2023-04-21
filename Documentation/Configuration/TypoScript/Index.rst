..  include:: /Includes.rst.txt


..  _typoscript:

==========
TypoScript
==========

`service_bw2` needs some basic TypoScript configuration. To do so you have
to add an +ext template to either the root page of your website or to a
specific page which contains the `service_bw2` plugin.

..  rst-class:: bignums

1.  Locate page

    You have to decide where you want to insert the TypoScript template. Either
    root page or page with `service_bw2` plugin is OK.

2.  Create TypoScript template

    Switch to template module and choose the specific page from above in the
    pagetree. Choose `Click here to create an extension template` from the
    right frame. In the TYPO3 community it is also known as "+ext template".

3.  Add static template

    Choose `Info/Modify` from the upper selectbox and then click
    on `Edit the whole template record` button below the little table. On
    tab `Includes` locate the section `Include static (from extension)`. Use
    the search below `Available items` to search for `service_bw2`. Hopefully
    just one record is visible below. Choose it, to move that record to
    the left.

4.  Save

    If you want you can give that template a name on tab "General", save
    and close it.

5.  Constants Editor

    Choose `Constant Editor` from the upper selectbox.

6.  `service_bw2` constants

    Choose `PLUGIN.TX_SERVICEBW2` from the category selectbox to show
    just `service_bw2` related constants

7.  Configure constants

    Adapt the constants to your needs. We prefer to set all
    these `pidOfListPage` and `pidOfDetailPage` constants. That prevents you
    from setting all these PIDs in each plugin individual.

8.  Configure TypoScript

    As constants will only allow modifying a fixed selection of TypoScript
    you also switch to `Info/Modify` again and click on `Setup`. Here you have
    the possibility to configure all `service_bw2` related configuration.

View
====

..  confval:: templateRootPaths

    :type: array
    :Default: EXT:service_bw2/Resources/Private/Templates/
    :Path: plugin.tx_servicebw2.view.*

    You can override our Templates with your own SitePackage extension. We
    prefer to change this value in TS Constants.

..  confval:: partialRootPaths

    :type: array
    :Default: EXT:service_bw2/Resources/Private/Partials/
    :Path: plugin.tx_servicebw2.view.*

    You can override our Partials with your own SitePackage extension. We
    prefer to change this value in TS Constants.

..  confval:: layoutsRootPaths

    :type: array
    :Default: EXT:service_bw2/Resources/Layouts/Templates/
    :Path: plugin.tx_servicebw2.view.*

    You can override our Layouts with your own SitePackage extension. We
    prefer to change this value in TS Constants.

Settings
========

..  confval:: overridePageTitle

    :type: boolean
    :Default: 0 (false)
    :Path: plugin.tx_servicebw2.settings

    Set to 1 (true) to override the page title in show actions with item name.


..  confval:: organisationseinheiten.pidOfListPage

    :type: int
    :Default: (none)
    :Path: plugin.tx_servicebw2.settings

    If you need a link in a detail view to go back into list view please fill
    that value with a page UID where the plugin
    for Organisationseinheiten resides.

..  confval:: organisationseinheiten.pidOfDetailPage

    :type: int
    :Default: (none)
    :Path: plugin.tx_servicebw2.settings

    For design resons it may make sense to link an Organisationseinheit onto
    its own page UID.

..  confval:: leistungen.pidOfListPage

    :type: int
    :Default: (none)
    :Path: plugin.tx_servicebw2.settings

    If you need a link in a detail view to go back into list view please fill
    that value with a page UID where the plugin for Leistungen resides.

..  confval:: leistungen.pidOfDetailPage

    :type: int
    :Default: (none)
    :Path: plugin.tx_servicebw2.settings

    For design resons it may make sense to link a Leistung onto its
    own page UID.

..  confval:: lebenslagen.pidOfListPage

    :type: int
    :Default: (none)
    :Path: plugin.tx_servicebw2.settings

    If you need a link in a detail view to go back into list view please fill
    that value with a page UID where the plugin for Lebenslagen resides.

..  confval:: lebenslagen.pidOfDetailPage

    :type: int
    :Default: (none)
    :Path: plugin.tx_servicebw2.settings

    For design reasons it may make sense to link a Lebenslage onto its
    own page UID.

=====
Maps2
=====

If not already done, then you need to configure maps2, because `service_bw2`
has a `maps2` integration for departments. Take a look into
the `maps2` documentation for that.
