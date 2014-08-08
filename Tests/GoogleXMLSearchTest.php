<?php
/**
 * @Author: thePanz
 */

namespace Liip\SearchBundle\Tests;


use Liip\SearchBundle\Google\GoogleXMLSearch;

class GoogleXMLSearchTest extends \PHPUnit_Framework_TestCase {

    /**
     * @return array
     */
    function googleUrlTestProvider() {
        return array(
            array(
                array(
                    // googleApiKey                            // googleSearchKey
                    'XLhxSyDG32q73rN-HGFXevQ_py1FhzgjV-dFgh4', '817338198764983948238:mksjeew9awd',
                    // googleSearchAPIUrl
                    'https://www.googleapis.com/customsearch/v1',
                    // restrictToSite   restrictToLabels
                    null,               null
                ),
                // Query
                'SomèTéx#',
                // Lang     Start       Limit
                'en',       '1',        '10',
                // Expected url
                'https://www.googleapis.com/customsearch/v1?key=XLhxSyDG32q73rN-HGFXevQ_py1FhzgjV-dFgh4&cx=817338198764983948238:mksjeew9awd&start=1&num=10&lr=lang_en&hl=en&q=Som%C3%A8T%C3%A9x%23'
            ),
            array(
                array(
                    'XLhxSyDG32q73rN-HGFXevQ_py1FhzgjV-dFgh4', '817338198764983948238:mksjeew9awd',
                    'https://www.googleapis.com/customsearch/v1',
                    null,               null
                ),
                'text-text_text.text', 'en', '1', '10',
                'https://www.googleapis.com/customsearch/v1?key=XLhxSyDG32q73rN-HGFXevQ_py1FhzgjV-dFgh4&cx=817338198764983948238:mksjeew9awd&start=1&num=10&lr=lang_en&hl=en&q=text%2Dtext%5Ftext%2Etext'
            ),
            array(
                array(
                    'XLhxSyDG32q73rN-HGFXevQ_py1FhzgjV-dFgh4', '817338198764983948238:mksjeew9awd',
                    'https://www.googleapis.com/customsearch/v1',
                    null,               null
                ),
                'th-query', 'en', '1', '10',
                'https://www.googleapis.com/customsearch/v1?key=XLhxSyDG32q73rN-HGFXevQ_py1FhzgjV-dFgh4&cx=817338198764983948238:mksjeew9awd&start=1&num=10&lr=lang_en&hl=en&q=th%2Dquery'
            ),
            array(
                array(
                    'XLhxSyDG32q73rN-HGFXevQ_py1FhzgjV-dFgh4', '817338198764983948238:mksjeew9awd',
                    'https://www.googleapis.com/customsearch/v1',
                    'mysite.com',       null
                ),
                'th-query', 'en', '1', '10',
                'https://www.googleapis.com/customsearch/v1?key=XLhxSyDG32q73rN-HGFXevQ_py1FhzgjV-dFgh4&cx=817338198764983948238:mksjeew9awd&start=1&num=10&lr=lang_en&hl=en&siteSearch=mysite.com&q=th%2Dquery'
            ),
        );
    }


    /**
     * @dataProvider googleUrlTestProvider
     */
    function testConnection($googleSettings, $query, $lang, $start, $limit, $expectedUrl) {

        list($googleApiKey, $googleSearchKey, $googleSearchAPIUrl, $restrictToSite, $restrictToLabels) = $googleSettings;

        $g = new GoogleXMLSearch($googleApiKey, $googleSearchKey, $googleSearchAPIUrl, $restrictToSite, $restrictToLabels);

        $this->assertEquals($expectedUrl, $g->getRequestUrl($query, $lang, $start, $limit));

    }


    /**
     * @return array
     */
    function googleBasicSetting() {
        return  array(
            // googleApiKey                            // googleSearchKey
            'XLhxSyDG32q73rN-HGFXevQ_py1FhzgjV-dFgh4', '817338198764983948238:mksjeew9awd',
            // googleSearchAPIUrl
            'https://www.googleapis.com/customsearch/v1',
            // restrictToSite   restrictToLabels
            null,               null
        );
    }


    /**
     * Test empty query result
     */
    function testEmptyQuery() {
        $googleSettings = $this->googleBasicSetting();
        list($googleApiKey, $googleSearchKey, $googleSearchAPIUrl, $restrictToSite, $restrictToLabels) = $googleSettings;

        $g = new GoogleXMLSearch($googleApiKey, $googleSearchKey, $googleSearchAPIUrl, $restrictToSite, $restrictToLabels);
        $expected = array('items' => array(), 'information' => array());

        $this->assertEquals($expected, $g->getSearchResults('', 'en', 1, 10));
        $this->assertEquals($expected, $g->getSearchResults(null, 'en', 1, 10));
    }

}