<h1>Shopware 6 plugin for KK Course Recommendation System</h1>

Plugin works on Shopware 6.6.x - was tested on Shopware 6.6.10.2

Plugin relies on autowire and autoconfigure for services

Plugin uses JSON file as a source of providers data - file should be placed in location:<br/>
files/CourseBundleRecommendation/providers.json

API endpoint is available on Storefront for now - it could de moved to Store API to restrict access

Address for endpoint:<br/>
/course/bundle-recommendation
