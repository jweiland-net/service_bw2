# TYPO3 Extension `service_bw2`
[![Packagist][packagist-logo-stable]][extension-packagist-url]
[![Latest Stable Version][extension-build-shield]][extension-ter-url]
[![Total Downloads][extension-downloads-badge]][extension-packagist-url]
[![Monthly Downloads][extension-monthly-downloads]][extension-packagist-url]
[![TYPO3 13.4][TYPO3-shield]][TYPO3-13-url]

![Build Status](https://github.com/jweiland-net/service_bw2/actions/workflows/ci.yml/badge.svg)

Interface for Service BW

## Description:
This TYPO3 extension integrates with service-bw, providing authorities with valuable information from the service-bw portal. Authorities, including participants in the state administration network (LVN) and municipalities in the KVN municipal administration network, can access detailed information via the AdminCenter on the internal BW portal.

## Features:

- Access service-bw information directly within TYPO3.
- Integration with the service-bw AdminCenter.
- Retrieve and display relevant data on government accounts and the special electronic government mailbox (beBPo).
- Seamless connection to the state administration network (LVN) and KVN municipal administration network.

## Installation:

### Installation using Composer

The recommended way to install the extension is using Composer.

Run the following command within your Composer based TYPO3 project:

```
composer require jweiland/service-bw2
```

### Installation using classic way

1. Install the extension via TYPO3 Extension Manager.
2. Configure access to the internal BW portal within TYPO3 settings.

## Usage:

1. Navigate to the TYPO3 backend.
2. Access the service-bw information through the integrated extension.
3. Explore AdminCenter functionalities, documents, and instructions.
4. Integrate service-bw data into your TYPO3-based websites.
5. Manage government accounts and special electronic government mailboxes.

## Resources:

- [Extension URL](https://extensions.typo3.org/extension/service_bw2)
- [Service-BW Portal](https://bw-portal.bwl.de/service-bw)
- [AdminCenter Documentation](https://www.service-bw.de/zufi/cms/informationsseite-zum-behoerdenkonto-und-besonderen-elektronischen-behoerdenpostfach-bebpo)

For more details, refer to the documentation provided with the extension.

**Note:** Ensure proper configuration of network access for authorities participating in LVN or KVN. Contact the network service provider for assistance.

This extension simplifies the interaction between TYPO3 and service-bw, enhancing the efficiency of authorities in accessing and utilizing valuable information.


<!-- MARKDOWN LINKS & IMAGES -->

[extension-build-shield]: https://poser.pugx.org/jweiland/service-bw2/v/stable.svg?style=for-the-badge

[extension-downloads-badge]: https://poser.pugx.org/jweiland/service-bw2/d/total.svg?style=for-the-badge

[extension-monthly-downloads]: https://poser.pugx.org/jweiland/service-bw2/d/monthly?style=for-the-badge

[extension-ter-url]: https://extensions.typo3.org/extension/service_bw2/

[extension-packagist-url]: https://packagist.org/packages/jweiland/service-bw2/

[packagist-logo-stable]: https://img.shields.io/badge/--grey.svg?style=for-the-badge&logo=packagist&logoColor=white

[TYPO3-13-url]: https://get.typo3.org/version/13

[TYPO3-shield]: https://img.shields.io/badge/TYPO3-13.4-green.svg?style=for-the-badge&logo=typo3
