..  include:: /Includes.rst.txt


..  _admin:

=============
Administrator
=============

..  toctree::
    :maxdepth: 2
    :titlesonly:

    Upgrade/Index

Declaration of Innocence
========================

All endpoints of the Service BW API are in German. Every method, every
property, every explanation, every thing is in German. We have
tried our best, but as we have a request class for each API endpoint,
we had trouble finding the right request class for the German
API endpoint. That's why we have adapted the German API endpoint to our
request classes. That's why you will find German PHP classes in our
extension. We are not happy with this situation, but it does
reduces our support and simplifies the extensibility of `service_bw2` a lot.

Service BW API
==============

Service BW REST API comes with two API versions: V1 and V2.

V1 contains a note, that you should use V2, if possible.
V2 contains a note, that it is still in active development.

Currently, `service_bw2` uses the Service BW REST API in version 2.

Here are the official links to the API documentation:

*   `REST API V1 <https://sgw.service-bw.de/rest-documentation/>`__
*   `REST API V2 <https://sgw.service-bw.de/rest-v2/documentation/>`__

If you're interested you can authorize there and test the API endpoints.
