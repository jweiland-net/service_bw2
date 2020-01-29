.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _changelog:

ChangeLog
=========

**Version 3.0.0**

- Switched Plugin Namespace in TS from plugin.tx_servicebw2_servicebw to plugin.tx_servicebw2
- Add TypoScriptService to merge filled TS settings into empty FlexForm settings.

**Version 2.1.1**

- Remove strict type from processRequest in ServiceBwClient, as this method can also return
  null, array and string
- Switch over from StringFrontend to VariableFrontend. You have to clear Cache completely.
- Update Documentation

