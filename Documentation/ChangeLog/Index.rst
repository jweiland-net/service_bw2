..  include:: /Includes.rst.txt


..  _changelog:

=========
ChangeLog
=========

Version 6.0.2
=============

*   Do not try to index (EXT:solr) empty records
*   Create own TYPO3 log file (var/log/typo3_servicebw2_[hash])
*   Add flash messages to show actions, if a record could not be found

Version 6.0.1
=============

*   Convert all parent IDs for filtering to int

Version 6.0.0
=============

*   Add TYPO3 11 compatibility
*   Remove TYPO3 9 compatibility
*   We keep PHP 7.3 compatibility for better migration
*   Check TS path in OrganisationseinheitPoiCollectionUidViewHelper before
    using it
*   Migrate scheduler task to Symfony command
*   Remove old repo2model mapping
*   Rename TSConfig files to `*.tsconfig`
*   Better structure for WarmUpCommand
*   Set indent size in docs to 4 spaces
*   Rename DataHandler Hook class


Version 5.0.7
=============

*   Add .gitattributes
*   Use correct structure for headlines in documentation

Version 5.0.6
=============

*   Implement new structure to documentation
*   Check value for string before calling setPageTitle()

Version 5.0.5
=============

*   Catch and log exceptions while requesting Service BW API

Version 5.0.4
=============

*   Add exclude argument for textbloecke

Version 5.0.3
=============

*   Add lang attribute to all fluid templates
*   Use full set of method arguments to build cache identifier
*   Show textbloacke of type preamble in frontend again

Version 5.0.2
=============

*   Remove deprecated TCA option `enableMultiSelectFilterTextfield`

Version 5.0.1
=============

*   Delete solr documents for all other languages, too

Version 5.0.0
=============

..  hint::

    Updated Service BW API calls to the lot improved Version 2!
    There are a lot of changes but the public API classes
    `JWeiland\\ServiceBw2\\Utility\\TCAUtility` and
    `JWeiland\\ServiceBw2\\Utility\\ModelUtility` are compatible with earlier
    versions, so third party extensions that use service_bw2 should continue
    to work.

Text in quotation marks are original terms from Service BW and therefore in
German language.

*   Rewrite ServiceBwClient to be easier to understand and easier to use
*   Remove ServiceBwClient PostProcessors and ServiceBwClient PostProcessor hook
*   Remove all repositories that has been used for API requests
*   Remove all API v1 request classes
*   Replace all repository usages by the new request classes
*   Update fluid templates to work with latest jweiland musterprojekt template
*   Update fluid templates to work with API v2
*   Add contact persons to "Organisationseinheiten" detail view
*   Add electronic forms "Prozesse" to "Leistung" detail view
*   Update "Lebenslagen" list view from glossar to a tree
*   Remove TYPO3 v9 compatibility
*   Add event to modify Service BW API responses before they get cached (Hook)

Version 4.0.1
=============

*   Add missing Aspect Mapper for RouteEnhancer

Version 4.0.0
=============

*   Remove TYPO3 8 compatibility
*   Add TYPO3 10 compatibility

Version 3.0.1
=============

*   Region IDs will internally be used as arrays instead of comma separated
    values
*   If Region IDs are not known you can add AGS or ZIP to help finding
    Region IDs.
*   Update Documentation

Version 3.0.0
=============

*   Breaking: Switched Plugin Namespace in TS from
    plugin.tx_servicebw2_servicebw to plugin.tx_servicebw2
*   Add TypoScriptService to merge filled TS settings into empty
    FlexForm settings.
*   Add Fluid Namespace to all Fluid Templates
*   Use AbstractViewHelper of Typo3Fluid package
*   Add FlexForm overview to Page->show module
*   Add configuration for newContentElementWizard
*   Move tt_content changing TCA into TCA/Overrides

Version 2.1.1
=============

*   Remove strict type from processRequest in ServiceBwClient, as this method
    can also return null, array and string
*   Switch over from StringFrontend to VariableFrontend. You have to clear
    Cache completely.
*   Update Documentation
