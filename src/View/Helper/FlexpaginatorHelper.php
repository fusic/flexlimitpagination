<?php

namespace Flexpager\View\Helper;

use Cake\View\Helper\PaginatorHelper;

/**
 * Pagination Helper class for easy generation of pagination links.
 *
 * PaginationHelper encloses all methods needed when working with pagination.
 *
 * @property UrlHelper $Url
 * @property NumberHelper $Number
 * @property HtmlHelper $Html
 *
 * @link http://book.cakephp.org/3.0/en/views/helpers/paginator.html
 */
class FlexpaginatorHelper extends PaginatorHelper
{
    protected $flexPagerTemplate = '<a href={{url}}>{{content}}</a>';

    public function limitCandidate()
    {
        if (empty($this->_View->get('listCandidates'))) {
            return;
        }

        $listCandidates = $this->_View->get('listCandidates');

        $result = $this->createUrl($listCandidates);

        return $result;
    }

    /**
     * createUrl create Url From the params listCandidates.
     *
     * @param [type] $listCandidates ex) [5, 10, 20];
     *
     * @return url for another limits
     */
    protected function createUrl($listCandidates)
    {
        // current Url Except For limit.
        $flexUrl = $this->_View->get('flexUrl');
        $currentLimit = $this->_View->get('currentLimit');

        $conjunction = strpos($flexUrl, '?') ? '&' : '?';

        $result = '';
        foreach ($listCandidates as $candidates) {
            if ($candidates == $currentLimit) {
                continue;
            }
            $url = $flexUrl.$conjunction.'limit='.$candidates;

            $href = preg_replace('/{{url}}/', $url, $this->flexPagerTemplate);
            $href = preg_replace('/{{content}}/', $candidates, $href);

            $result .= $href;
        }

        return $result;
    }

    /**
     * setFlexPagerTemplate modify the original template
     * @param [string] $string must include "{{url}}" and "{{content}}".
     */
    public function setFlexPagerTemplate($string)
    {
        if (!strpos($string, '{{url}}')) {
            throw new \Exception('"{{url}}" is not included.');
        }
        if (!strpos($string, '{{content}}')) {
            throw new \Exception('"{{content}}" is not included.');
        }
        $this->flexPagerTemplate = $string;
    }
    
    /**
     * numbers follow the settings for limit.
     *
     * @param array $options
     *
     * @return string URL created by parent
     */
    public function numbers(array $options = [])
    {
        $currentLimit = $this->_View->get('currentLimit');
        if (empty($options['url']['limit'])) {
            $options['url']['limit'] = $currentLimit;
        }

        return parent::numbers($options);
    }
}
