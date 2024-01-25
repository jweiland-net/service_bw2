..  include:: /Includes.rst.txt


..  _solr:

==================
Solr Configuration
==================

The Solr integration in the ServiceBW extension allows indexing and searching
of service portal content. It covers indexer configuration and logging
configuration related to Solr.

The extension supports the integration of the following content into a Solr
search:

-   Organizational Units (Organisationseinheiten)
-   Life Situations (Lebenslagen)
-   Services (Leistungen)

Here are the steps to include default configuration to your TypoScript
configuration.

..  rst-class:: bignums

1.  Root page where solr is configured

    You have to insert the TypoScript template where the solr is configured.

2.  Create TypoScript template

    Switch to template module and choose the specific page from above in the
    pagetree. Choose `Click here to create an extension template` from the
    right frame. In the TYPO3 community it is also known as "+ext template".

3.  Add static template

    Choose `Info/Modify` from the upper selectbox and then click
    on `Edit the whole template record` button below the little table. On
    tab `Includes` locate the section `Include static (from extension)`. Use
    the search above `Available items` to search for `service_bw2`. Hopefully
    there will be a record `Service BW2 - Search` available. Choose it,
    to move that record to the left.

4.  Save

    If you want you can give that template a name on tab "General", save
    and close it.

5.  Constant Editor

    Choose `Constant Editor` from the upper selectbox.

6.  `service_bw2` constants

    Choose `PLUGIN.TX_SERVICEBW2` from the category selectbox to show
    just `service_bw2` related constants

7.  Configure constants

    Adapt the constants to your needs. We prefer to set all
    these detail page constants for solr indexing.

8.  Configure TypoScript

    As constants will only allow modifying a fixed selection of TypoScript
    you also switch to `Info/Modify` again and click on `Setup`. Here you have
    the possibility to configure all `service_bw2` related configuration.

Indexing Configuration
======================

Here are the included solr indexing configuration included with the extension.

..  code-block:: typoscript

    plugin.tx_solr.index.queue {
      tx_servicebw2_organisationsEinheiten {
        indexer {
          detailPage = {$plugin.tx_servicebw2.solr.organisationsEinheiten.detailPage}
        }
        fields {
          title = name
          content = processed_textbloecke
          organisationseinheit_textM = processed_organisationseinheit
          url = TEXT
          url {
            typolink.parameter = {$plugin.tx_servicebw2.solr.organisationsEinheiten.detailPage}
            typolink.additionalParams = &tx_servicebw2_organizationalunitsshow[id]={field:uid}&tx_servicebw2_organizationalunitsshow[action]=show&tx_servicebw2_organizationalunitsshow[controller]=Organisationseinheiten
            typolink.additionalParams.insertData = 1
            typolink.useCacheHash = 1
            typolink.returnLast = url
          }
        }
      }

      tx_servicebw2_lebenslagen {
        indexer {
          detailPage = {$plugin.tx_servicebw2.solr.lebenslagen.detailPage}
        }

        fields {
          title = name
          content = processed_textbloecke

          url = TEXT
          url {
            typolink.parameter = {$plugin.tx_servicebw2.solr.lebenslagen.detailPage}
            typolink.additionalParams = &tx_servicebw2_lifesituationsshow[id]={field:uid}&tx_servicebw2_lifesituationsshow[action]=show&tx_servicebw2_lifesituationsshow[controller]=Lebenslagen
            typolink.additionalParams.insertData = 1
            typolink.useCacheHash = 1
            typolink.returnLast = url
          }
        }
      }

      tx_servicebw2_leistungen {
        indexer {
          detailPage = {$plugin.tx_servicebw2.solr.leistungen.detailPage}
        }

        fields {
          title = name
          content = processed_textbloecke

          url = TEXT
          url {
            typolink.parameter = {$plugin.tx_servicebw2.solr.leistungen.detailPage}
            typolink.additionalParams = &tx_servicebw2_servicesshow[id]={field:uid}&tx_servicebw2_servicesshow[action]=show&tx_servicebw2_servicesshow[controller]=Leistungen
            typolink.additionalParams.insertData = 1
            typolink.useCacheHash = 1
            typolink.returnLast = url
          }
        }
      }
    }

    plugin.tx_solr.logging.indexing.queue.tx_servicebw2_organisationsEinheiten = 1
    plugin.tx_solr.logging.indexing.queue.tx_servicebw2_lebenslagen = 1
    plugin.tx_solr.logging.indexing.queue.tx_servicebw2_leistungen = 1

|

The above configuration is related to the TYPO3 extension Solr, which is used
for integrating Solr search functionality into a TYPO3 website. Let's break
down the configuration:

Indexing Configuration
----------------------

Three distinct Solr index queues are defined:

-   `tx_servicebw2_organisationsEinheiten`
-   `tx_servicebw2_lebenslagen`
-   `tx_servicebw2_leistungen`

Each index queue includes specific settings under the `indexer` section,
notably the `detailPage` setting, which specifies the detail page for the
corresponding content type.

Field Mapping
-------------

For each index queue, the `fields` section dictates the mapping of fields
from TYPO3 content to Solr fields. Key mappings include:

-   `title` and `content`: Mapping to standard Solr fields.
-   `organisationseinheit_textM`: A custom field for organizational units.
-   `url`: Dynamically generated URLs for linking to content in the Solr index.

URL Generation
--------------

The `url` field for each index queue is configured using TypoScript's `TEXT`
object. This allows for the dynamic generation of URLs based on specified
`typolink` parameters. These URLs are included in the Solr index for enhanced
navigation.

Logging Configuration
---------------------

Logging settings are configured for each index queue to facilitate monitoring
during the Solr indexing process. The logging is activated for the following
index queues:

-   `tx_servicebw2_organisationsEinheiten`
-   `tx_servicebw2_lebenslagen`
-   `tx_servicebw2_leistungen`

Content Types Covered
----------------------

This configuration is tailored for the ServiceBW extension and covers the
following content types for Solr indexing:

-   `tx_servicebw2_organisationsEinheiten`: Organizationalal Units
-   `tx_servicebw2_lebenslagen`: Life Situations
-   `tx_servicebw2_leistungen`: Services

By following this configuration, the Solr extension ensures that relevant
content fields are accurately mapped, URLs are dynamically generated, and
indexing actions are logged for monitoring purposes.
