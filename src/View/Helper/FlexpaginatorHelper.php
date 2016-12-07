<?php

namespace Flexpager\View\Helper;

use Cake\View\Helper;

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
class FlexpaginatorHelper extends Helper
{
    public function limitCandidate()
    {
        if (empty($this->_View->get('listCandidates'))) {
            return;
        }
        $flexUrl = $this->_View->get('flexUrl');
        $conjunction = strpos($flexUrl, '?') ? '&' : '?';

        $listCandidates = $this->_View->get('listCandidates');
        $limit = $listCandidates;
        $result = '';
        foreach ($listCandidates as $candidates) {
            $result .= '<a href="'.$flexUrl.$conjunction.'limit='.$candidates.'">'.$candidates.'</a>';
        }

        return $result;
    }
}
