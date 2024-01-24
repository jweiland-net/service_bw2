..  include:: /Includes.rst.txt


..  _upgrade:

Upgrade
=======

Version 7.0.0
-------------

In this release, we've addressed compatibility issues with TYPO3 12 LTS and
have streamlined compatibility by removing support for lower versions.
A crucial step in this version upgrade is to execute the upgrade wizard,
ensuring a smooth transition of all switchable controller actions to separate
plugins.

Version 6.0.0
-------------

We have migrated the Solr Indexer Task into a command. Please copy the values
of the old tasks, delete the tasks, create new scheduler tasks of
type `Execute console command`, choose `servicebw2::preparesolrindex` and save
the task. After the reload new fields for the chosen command will be visible.
Fill in the values from above and save again.

Version 5.0.0
-------------

We updated the whole extension because of the Service BW API Version 2. There
is a new much simpler ServiceBwClient which can be used for all API
requests (even for version 1).

We removed the post processors and post processor hook of ServiceBwClient. If
you added a custom or extended an existing one then keep in mind that these
no longer work.

If you added your own requests, you have to update them.
Use `JWeiland\\ServiceBw2\\Request\\AbstractRequest` as base and take a look
at the other request classes to build your own one. The newer ServiceBwClient
is much easier to understand so it should not take very long to migrate your
old request classes.

Custom fluid templates must also be updated due to the new object structure
from API v2. To do this, use the existing templates and go through
the templates piece by piece.

Third party extensions that use `JWeiland\\ServiceBw2\\Utility\\TCAUtility`
and `JWeiland\\ServiceBw2\\Utility\\ModelUtility` should not be affected.
We updated those classes but kept the public methods and properties.

Version 3.0.0
-------------

We have simplified the Plugin Configuration. That's why you have to change
your TS Setup and TS Constants from

:typoscript:`plugin.tx_servicebw2_servicebw`

to

:typoscript:`plugin.tx_servicebw2`

Version 2.1.1
-------------

Bugfix Release for TYPO3 9.

As there is no StringFrontend in Caching System of TYPO3 9 anymore we had to
switch over to VariableFrontend. Now the cache data itself will be stored in
another format, that's why you have to Clear all Caches.
