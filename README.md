<h1>Shopware 6 plugin for KK Course Recommendation System</h1>

Plugin works on Shopware 6.6.x - was tested on Shopware 6.6.10.2

Plugin relies on autowire and autoconfigure for services

Plugin uses JSON file as a source of providers data - file should be placed in location:

files/CourseBundleRecommendation/providers.json

Address for endpoint:

/course/bundle-recommendation

API endpoint is available on Storefront for now - it could de moved to Store API to restrict access

Logs can be written to dedicated channel

There could be configuration in Shopware Administration - rates and number of topics to consider could be changed there.
And file with provider data could be uploaded in Administration.

To run test need to be installed:

composer require --dev dev-tools

Running the tests:

./vendor/bin/phpunit --configuration="PATH_TO_PLUGIN_DIRECTORY"

i.e.

./vendor/bin/phpunit --configuration="custom/static-plugins/CourseBundleRecommendation"