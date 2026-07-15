# Crystal Anthill AppToolkit for Magento 2 
[![Latest Version](https://img.shields.io/packagist/v/crystalanthill/magento2-app-toolkit?style=flat-square)](https://packagist.org/packages/crystalanthill/magento2-app-toolkit)
[![License](https://img.shields.io/badge/License-OSL--3.0-green?style=flat-square)](LICENSE)

---

## What this module provides

- **Abstract data patch for adding products attribute sets**
- **[AttributeSetAssigner](Setup/AttributeSetAssigner.php)**
- **Abstract data patch to add options to product attribute [AddProductAttributeOptions](Setup/AddProductAttributeOptions.php)**
- **Add attribute to use in cart [catalog_attributes.xml](etc/catalog_attributes.xml)**

### next:
 - AddressAttributeAbstract
 - CustomerAttributeAbstract
 - ProductTaxAbstract
- **Data patch for adding a new store**
- **Data patch for adding new tax rates**


---
## Installation
```bash
composer require crystalanthill/magento2-app-toolkit
bin/magento module:enable CrystalAnthill_AppToolkit
bin/magento setup:upgrade
```
---
## Usage for module developers
### 1. Declare the dependency
`etc/module.xml`:
```xml
<module name="Vendor_YourModule">
    <sequence>
        <module name="CrystalAnthill_AppToolkit"/>
    </sequence>
</module>
```
`composer.json`:
```json
"require": {
    "crystalanthill/magento2-app-toolkit": "^1.0"
}
```

---

## Compatibility

| Platform | Version |
|---|---|
| Magento Open Source | 2.4.4 — 2.4.x |
| Adobe Commerce | 2.4.4 — 2.4.x |
| MageOS | 2.4.6+ |
| PHP | 8.1+ |

---

## License

[Open Software License 3.0 (OSL-3.0)](LICENSE)

---
<p align="center">
  Built by <a href="https://www.crystalanthill.com">Crystal Anthill Software Development</a>
</p>