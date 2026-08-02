# Vendor Inventory Manager — Sanitized WordPress Code Sample

This repository is a sanitized and refactored sample based on production marketplace work. Client branding, credentials, URLs, proprietary rules, and private integrations have been removed.

The sample demonstrates how I structure maintainable WordPress plugin code for a team environment:

- lightweight bootstrap file;
- PSR-4-style class organization;
- separate domain, infrastructure, REST, validation, and authorization concerns;
- WordPress REST API endpoints with capability and ownership checks;
- sanitization, validation, escaped output, and prepared SQL queries;
- activation-time database setup with indexed columns;
- no WordPress, WooCommerce, or third-party core-file modifications.

## What the sample does

It provides authenticated REST endpoints for vendors to create, list, update, and delete their own inventory records. Each record has an owner, title, SKU, status, cost, estimated value, and timestamps.

## Structure

```text
vendor-inventory-manager.php     Plugin bootstrap
src/Plugin.php                   Composition root and route registration
src/Infrastructure/Installer.php Database schema and activation
src/Domain/InventoryRepository.php Data-access layer
src/Rest/InventoryController.php HTTP/REST layer
src/Support/Authorization.php    Authentication, capability, and ownership rules
src/Support/Validation.php       Input validation and normalization
uninstall.php                    Safe opt-in cleanup
```

## Installation

1. Copy the folder to `wp-content/plugins/vendor-inventory-manager`.
2. Activate **Vendor Inventory Manager**.
3. Use the routes under `/wp-json/vim/v1/inventory` while authenticated with a valid WordPress REST nonce.

## REST endpoints

- `GET /vim/v1/inventory`
- `POST /vim/v1/inventory`
- `PUT /vim/v1/inventory/{id}`
- `DELETE /vim/v1/inventory/{id}`

## Team workflow

For production work I use feature branches, focused commits, pull-request review, staging validation, and documented schema/API changes. Business rules stay out of templates, and integrations are added through WordPress hooks and services rather than core edits.

## Privacy note

This is not the full production client repository. It intentionally contains only generic, non-confidential code suitable for technical review.

