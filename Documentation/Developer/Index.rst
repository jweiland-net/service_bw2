..  include:: /Includes.rst.txt


..  _developer:

================
Developer Corner
================

..  _developer-api:

Important notes
===============

We are using the german method names of the Service BW API in our extension
to make it easier to extend/understand the extension.

Hooks / Events
==============

Modify response object
----------------------

Use the `ModifyServiceBwResponseEvent` event to modify the response object of
the Service BW API before it gets cached. The event dispatches before paginated
requests are merged together.

Add an event listener class that uses the event to modify the request.

Example: Modify the URL of Service BW online forms (called: Prozesse):
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

..  code-block:: php

    <?php

    declare(strict_types=1);

    namespace ThisIs\MySitePackage\Listener;

    use JWeiland\ServiceBw2\Client\Event\ModifyServiceBwResponseEvent;
    use TYPO3\CMS\Core\Utility\StringUtility;

    class ModifyServiceBwResponseListener
    {
        public function __invoke(ModifyServiceBwResponseEvent $event): void
        {
            if (StringUtility::beginsWith($event->getPath(), '/portal/leistungsdetails/')) {
                $responseBody = $event->getResponseBody();
                foreach ($responseBody['prozesse'] as &$prozess) {
                    $prozess['url'] = str_replace('www.', 'cityname.', $prozess['url']);
                }
                $event->setResponseBody($responseBody);
            }
        }
    }


Then register the event in your own Site Package so TYPO3 is able to find
the listener.

..  code-block:: yaml

    # EXT:my_site_package/Configuration/Services.yaml
    services:
      ThisIs\MySitePackage\Listener\ModifyServiceBwResponseListener:
        tags:
          - name: event.listener
            identifier: 'ext-mysitepackage/servicebw-modifyrequest'
            event: JWeiland\ServiceBw2\Client\Event\ModifyServiceBwResponseEvent
