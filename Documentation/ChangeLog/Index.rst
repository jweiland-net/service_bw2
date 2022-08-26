.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _changelog:

ChangeLog
=========

**Version 5.0.4**

- Add exclude argument for textbloecke

**Version 5.0.3**

- Add lang attribute to all fluid templates
- Use full set of method arguments to build cache identifier
- Show textbloacke of type preamble in frontend again

**Version 5.0.2**

- Remove deprecated TCA option `enableMultiSelectFilterTextfield`

**Version 5.0.1**

- Delete solr documents for all other languages, too

**Version 5.0.0**

.. hint::

   Updated Service BW API calls to the lot improved Version 2!
   There are a lot of changes but the public API classes
   `JWeiland\\ServiceBw2\\Utility\\TCAUtility` and `JWeiland\\ServiceBw2\\Utility\\ModelUtility`
   are compatible with earlier versions, so third party extensions that use service_bw2 should
   continue to work.

Text in quotation marks are original terms from Service BW and therefore in German language.

- Rewrite ServiceBwClient to be easier to understand and easier to use
- Remove ServiceBwClient PostProcessors and ServiceBwClient PostProcessor hook
- Remove all repositories that has been used for API requests
- Remove all API v1 request classes
- Replace all repository usages by the new request classes
- Update fluid templates to work with latest jweiland musterprojekt template
- Update fluid templates to work with API v2
- Add contact persons to "Organisationseinheiten" detail view
- Add electronic forms "Prozesse" to "Leistung" detail view
- Update "Lebenslagen" list view from glossar to a tree
- Remove TYPO3 v9 compatibility
- Add event to modify Service BW API responses before they get cached (Hook)

**Version 4.0.1**

- Add missing Aspect Mapper for RouteEnhancer

**Version 4.0.0**

- Remove TYPO3 8 compatibility
- Add TYPO3 10 compatibility

**Version 3.0.1**

- Region IDs will internally be used as arrays instead of comma separated values
- If Region IDs are not known you can add AGS or ZIP to help finding Region IDs.
- Update Documentation

**Version 3.0.0**

- Breaking: Switched Plugin Namespace in TS from plugin.tx_servicebw2_servicebw to plugin.tx_servicebw2
- Add TypoScriptService to merge filled TS settings into empty FlexForm settings.
- Add Fluid Namespace to all Fluid Templates
- Use AbstractViewHelper of Typo3Fluid package
- Add FlexForm overview to Page->show module
- Add configuration for newContentElementWizard
- Move tt_content changing TCA into TCA/Overrides

**Version 2.1.1**

- Remove strict type from processRequest in ServiceBwClient, as this method can also return
  null, array and string
- Switch over from StringFrontend to VariableFrontend. You have to clear Cache completely.
- Update Documentation

