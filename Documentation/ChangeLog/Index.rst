.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _changelog:

ChangeLog
=========

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

