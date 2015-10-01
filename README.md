magento-api-tool
================

Naive tool for querying Magento APIs

Include RemoteService.php

Create a configuration file with the following data:

    addr = '<address_to_api>'
    user = '<api_username>'
    pass = '<api_key>'
    wsi_compliance = <boolean>
    basic_auth_user = '<basic_auth_user>'
    basic_auth_pw = '<basic_auth_password>'

Set the configuration file to use:

    php api.php -c <configuration_file>

Then use the appropriate script, e.g.:

    php Command/salesOrderList.php
