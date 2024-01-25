..  include:: /Includes.rst.txt


..  _configuration:

=============
Configuration
=============

..  toctree::
    :maxdepth: 2
    :titlesonly:

    Extension/Index
    TypoScript/Index
    Maps2/Index
    Solr/Index

Please follow these steps to configure `service_bw2`:

..  rst-class:: bignums

1.  Extension Settings

    Login to TYPO3 backend as an admin or system maintainer and chose
    `Settings` from the left menu. Click on `Configure Extensions` and chose
    `service_bw2`. Fill in the needed values as described here:
    :ref:`extensionSettings`

2.  Scheduler Tasks

    `service_bw2` does not work with LIVE data from Service BW API. It only
    works with cached data. That prevents TYPO3 to show error messages in
    frontend, if Service BW API is temporary not available.

    Please head over to the scheduler module in TYPO3 backend and create
    a new task:

    *   Class `Execute console commands`
    *   Type: `recurring`
    *   Frequency: We recommend `86400`
    *   Scheduleable Command: `servicebw:cachewarmup`
    *   Click `Save` to reload and show the further options:
    *   Activate option: `include-lebenslagen`
    *   Activate option: `include-leistungen`
    *   Activate option: `include-organisationseinheiten`
    *   Option `locales`: leave empty, to collect records for all
        allowed languages
    *   Save and close the task to return to the task overview

    Execute the task manually the first time.

    ..  warning::

        If there are a lot of records to synchronize, it may happen that you
        will get an error after ~90-240 seconds (PHP:max_execution_time).
        Don't worry, the task will still run in background. Click on
        `Scheduler` again and you will see that task is still running. Reload
        the Scheduler page until the task has finished synchronizing all
        records.

3.  Create Pages

    Now you need to create some pages for list and detail view for
    all services (Organisationseinheiten, Lebenslagen, Leistungen) you
    want to show:

    ..  figure:: ../Images/Configuration/PageTree.png
        :alt: Example Page Tree

4.  Configure TypoScript

    We prefer to set at least all the PIDs in TS settings. That will prevent
    you to set all these PIDs in each plugins individually.

    See :ref:`typoscript` how to set defaults for all plugins.

5.  Add Plugins

    For all created pages you need to add the `Service BW` plugin.

    ..  figure:: ../Images/Configuration/ChoosePlugin.png
        :alt: Choose plugin from content element wizard

    The plugin contains a selectbox to choose the `Display mode`. Select
    the desired display mode for your page.

    In case of `Organisationseinheiten list view` there is an additional
    field you should fill `Choose items to display`. We prefer choosing the
    first item from the left list element to show all available items in
    frontend.

    If you have configured the PIDs with help of TypoScript you can save
    the plugin now. Else you have to set all these PIDs for list and detail
    view individually.
