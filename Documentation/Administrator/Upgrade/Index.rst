..  include:: /Includes.rst.txt


..  _upgrade:

=======
Upgrade
=======

Version 9.0.0
=============

With this release, we have introduced significant changes to the extension.
Although the major version has been increased, this version remains compatible
with TYPO3 13 only. The following sections describe the breaking changes
in detail.

Removed ModifyServiceBwResponseEvent
------------------------------------

This event was originally introduced to add further data to the resulting cache
entry of a requested record.

With the newly implemented cache strategy, cache data can now be retrieved by
using a UID. This makes it possible to relate cache records more directly and
removes the need for additional enrichment of cached data.

If you used this event, we recommend enriching the data before frontend output
by using DataProcessors instead.

Removed SucheController
-----------------------

We have renamed the :php:`SucheController` to :php:`SearchController`.

Removed ClearCacheHook
----------------------

As all fetched data will not be stored in TYPO3 cache anymore, there is no need
to clear any cache with this class anymore.

Removed LeistungenHelper
------------------------

The :php:`LeistungenHelper` has been removed because its responsibilities were
unclear and the implementation caused unnecessary additional API calls.

Previously, data fetched from the Service BW API triggered an event, which then
called the extension's own :php:`LeistungenEventListener`. This listener made an
additional request to the Service BW API and stored the result in a separate
TYPO3 cache that was only used by a ViewHelper.

This workflow has been removed in favor of a simpler and more transparent data
handling approach.

Removed AlphabeticalIndexUtility
--------------------------------

We have migrated :php:`AlphabeticalIndexUtility` from a utility class with
static method calls to :php:`AlphabeticalIndexService`.

Removed ModelUtility
--------------------

We have removed :php:`ModelUtility`. Utility classes should never have any
dependencies to further services. For migration please use our new
Repository classes or make your own calls with our brand new Service BW
Client class in your own TYPO3 Extension.

Removed ServiceBwUtility
------------------------

The previous configuration suggested that custom repository classes could be
provided for Service BW integrations. However, this was misleading:
:php:`ServiceBwUtility` only supported a fixed set of repository classes, and it
was not possible to add or remove repositories dynamically.

To make this limitation explicit, we removed the dynamic repository concept from
the extension. Instead, the available controller types are now represented by
the new :php:`ControllerTypeEnum`.

Use the new :php:`RepositoryFactory` and :php:`ProviderFactory` classes together
with :php:`ControllerTypeEnum` to retrieve the supported repository and provider
instances.

Removed TCAUtility
------------------

With the removal of this class we could get rid of all the service_bw2
dependencies in 3 further of our extensions. This helps us a lot while upgrading
the extensions individual. No need to wait for service_bw2 to be ready.

For migration: Please implement the TCA for `selectMultipleSideBySide` on your
own. Create a user-func for `itemsProcFunc` or create your own TCA render-type
to fill the selectbox with Organisationseinheiten. Please use our new
:php:`OrganisationseinheitenRepository` to retrieve the data.

Removed LeistungenAdditionalDataViewHelper
------------------------------------------

The :php:`LeistungenAdditionalDataViewHelper` has been removed as part of the
removal of :php:`LeistungenHelper` described above.

It was previously used to check whether related "formulare" and "prozesse"
existed. This check is now handled through the new :php:`Record` model, which is
populated with all data fetched from the Service BW API.

Since the required data is now available directly on the model, the existence
check can be implemented more simply and without the previous ViewHelper.

Removed Request Folder
----------------------

We have removed full `Request` folder. Yes, there is a new `Request` folder in
`Classes/Client`, but these are not copies, these are complete new PHP classes
for our brand new Service BW Client class.

Removed LocalizationHelper
--------------------------

The :php:`LocalizationHelper` has been removed because it was not clear whether
the handled language referred to a TYPO3 language or to a language from the
Service BW API.

In addition, access to the global :php:`TYPO3_REQUEST` object should be avoided.

As an alternative, the new :php:`LanguageHelper` has been introduced. Its method
names clearly indicate which values are expected as input and which values are
returned. This makes it explicit in every place whether a TYPO3 language or a
Service BW language is being handled.

Authorization Handling
----------------------

We removed the authentication via username and password entirely from the
extension. You no longer need to store sensitive login credentials in any
configuration.

Following the official Service BW API guidelines, the extension now exclusively
relies on long-lived Bearer Tokens.

**Benefits of this change:**

*   **Security:** No master credentials are saved in the TYPO3 database or
    configuration files.
*   **Simplicity:** The extension logic is reduced, because login handshakes and
    token refresh cycles are no longer necessary.
*   **Stability:** The generated tokens are permanently valid according to the
    Service BW API.

You can request a new Bearer Token in the official `Service BW API documentation
<https://sgw.service-bw.de/rest-v2/documentation/>`__ under the section
:guilabel:`Authentifizierung: Token`. For detailed instructions on how to apply
this token within your TYPO3 installation, please refer to the configuration
section.



Version 8.0.0
=============

In this release, we've addressed compatibility issues with TYPO3 13 LTS and
have streamlined compatibility by removing support for lower versions.

Version 7.0.0
=============

In this release, we've addressed compatibility issues with TYPO3 12 LTS and
have streamlined compatibility by removing support for lower versions.
A crucial step in this version upgrade is to execute the upgrade wizard,
ensuring a smooth transition of all switchable controller actions to separate
plugins.

Version 6.0.0
=============

We have migrated the Solr Indexer Task into a command. Please copy the values
of the old tasks, delete the tasks, create new scheduler tasks of
type `Execute console command`, choose `servicebw2::preparesolrindex` and save
the task. After the reload new fields for the chosen command will be visible.
Fill in the values from above and save again.

Version 5.0.0
=============

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
=============

We have simplified the Plugin Configuration. That's why you have to change
your TS Setup and TS Constants from

:typoscript:`plugin.tx_servicebw2_servicebw`

to

:typoscript:`plugin.tx_servicebw2`

Version 2.1.1
=============

Bugfix Release for TYPO3 9.

As there is no StringFrontend in Caching System of TYPO3 9 anymore we had to
switch over to VariableFrontend. Now the cache data itself will be stored in
another format, that's why you have to Clear all Caches.
