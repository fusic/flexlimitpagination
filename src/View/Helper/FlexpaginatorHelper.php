<?php

namespace Flexpager\View\Helper;

use Cake\View\Helper\PaginatorHelper;
use Cake\Routing\Router;

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
    protected $flexActivePagerTemplate = '<a href="#" class="active">{{content}}</a>';
    protected $flexPagerFooter = '';
    protected $defaultPagerLimit = 20;

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
        if (empty($currentLimit)) {
            $currentLimit = $this->defaultPagerLimit;
        }

        $result = '';
        foreach ($listCandidates as $candidates) {
            if ($candidates == $currentLimit) {
                $result .= preg_replace('/{{content}}/', $candidates . $this->flexPagerFooter, $this->flexActivePagerTemplate);
            } else {
                // limit情報をセット
                $flexUrl['limit'] = $candidates;
                $url = Router::url($flexUrl);

                $href = preg_replace('/{{url}}/', $url, $this->flexPagerTemplate);
                $href = preg_replace('/{{content}}/', $candidates . $this->flexPagerFooter, $href);

                $result .= $href;
            }
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
     * setActiveFlexPagerTemplate modify the original template
     * @param [string] $string must include "{{url}}" and "{{content}}".
     */
    public function setActiveFlexPagerTemplate($string)
    {
        if (!strpos($string, '{{content}}')) {
            throw new \Exception('"{{content}}" is not included.');
        }
        $this->flexActivePagerTemplate = $string;
    }

    /**
     * setFlexPagerFooter modify the original template
     * @param [string] $string must include "{{url}}" and "{{content}}".
     */
    public function setFlexPagerFooter($string)
    {
        $this->flexPagerFooter = $string;
    }

    /**
     * setFlexPagerFooter modify the original template
     * @param [string] $string must include "{{url}}" and "{{content}}".
     */
    public function setDefaultPagerLimit($string)
    {
        $this->defaultPagerLimit = $string;
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
        /**
     * prev follow the settings for limit.
     *
     * @param string $prev
     * @param array $options
     *
     * @return string URL created by parent
     */
    public function prev($title = '<< Previous', array $options = [])
    {
        $currentLimit = $this->_View->get('currentLimit');
        if (empty($options['url']['limit'])) {
            $options['url']['limit'] = $currentLimit;
        }

        return parent::prev($title, $options);
    }
        /**
     * numbers follow the settings for limit.
     *
     * @param string $next
     * @param array $options
     *
     * @return string URL created by parent
     */
    public function next($title = 'Next >>', array $options = [])
    {
        $currentLimit = $this->_View->get('currentLimit');
        if (empty($options['url']['limit'])) {
            $options['url']['limit'] = $currentLimit;
        }

        return parent::next($title, $options);
    }

    /**
     * numbers follow the settings for limit.
     *
     * @param string $first
     * @param array $options
     *
     * @return string URL created by parent
     */
    public function first($first = '<< first', array $options = [])
    {
        $currentLimit = $this->_View->get('currentLimit');
        if (empty($options['url']['limit'])) {
            $limit = $currentLimit;
        }

        $options += [
            'url' => [],
            'model' => $this->defaultModel(),
            'escape' => true
        ];

        $params = $this->params($options['model']);

        if ($params['pageCount'] <= 1) {
            return false;
        }

        $out = '';
        if (is_int($first) && $params['page'] >= $first) {
            dump($first);
            for ($i = 1; $i <= $first; $i++) {
                $url = array_merge($options['url'], ['page' => $i]);
                $out .= $this->templater()->format('number', [
                    'url' => $this->generateUrl($url, $options['model']),
                    'text' => $i
                ]);
            }
        } elseif ($params['page'] > 1 && is_string($first)) {
            $first = $options['escape'] ? h($first) : $first;
            $out .= $this->templater()->format('first', [
                'url' => $this->generateUrl(['page' => 1, 'limit' => $limit], $options['model']),
                'text' => $first
            ]);
        }

        return $out;
    }

    /**
     * numbers follow the settings for limit.
     *
     * @param string $last
     * @param array $options
     *
     * @return string URL created by parent
     */
    public function last($last = 'last >>', array $options = [])
    {
        $currentLimit = $this->_View->get('currentLimit');
        if (empty($options['url']['limit'])) {
            $limit = $currentLimit;
        }

        $options += [
            'model' => $this->defaultModel(),
            'escape' => true,
            'url' => []
        ];
        $params = $this->params($options['model']);

        if ($params['pageCount'] <= 1) {
            return false;
        }

        $out = '';
        $lower = (int)$params['pageCount'] - (int)$last + 1;

        if (is_int($last) && $params['page'] <= $lower) {
            for ($i = $lower; $i <= $params['pageCount']; $i++) {
                $url = array_merge($options['url'], ['page' => $i]);
                $out .= $this->templater()->format('number', [
                    'url' => $this->generateUrl($url, $options['model']),
                    'text' => $i
                ]);
            }
        } elseif ($params['page'] < $params['pageCount'] && is_string($last)) {
            $last = $options['escape'] ? h($last) : $last;
            $out .= $this->templater()->format('last', [
                'url' => $this->generateUrl(['page' => $params['pageCount'], 'limit' => $limit], $options['model']),
                'text' => $last
            ]);
        }

        return $out;
    }
}
