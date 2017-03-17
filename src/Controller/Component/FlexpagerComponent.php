<?php

namespace Flexpager\Controller\Component;

use Cake\Controller\Component\PaginatorComponent;
use Cake\Routing\Router;

class FlexpagerComponent extends PaginatorComponent
{
    /**
     * $_listCandidates ex) [5, 10, 20, 50]
     */
    protected $_listCandidates = [];

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->controller = $this->_registry->getController();

        if (!empty($this->controller->paginate)) {
            $pagerConf = $this->controller->paginate;

            if (!empty($pagerConf['listCandidates'])) {
                $this->setListCandidates($pagerConf['listCandidates']);
                unset($pagerConf['listCandidates']);
            }
            // $this->_defaultConfig += $pagerConf;
            $this->_defaultConfig = array_merge($this->_defaultConfig, $pagerConf);
        }
    }

    /**
     * paginate This method is overriding  Cake\Controller\Component\PaginatorComponent.
     */
    public function paginate($object, array $settings = [])
    {
        $query = $this->request->query;

        if (!empty($query['limit']) && is_numeric($query['limit'])) {
            $this->_defaultConfig['limit'] = $query['limit'];
        }

        // create url.
        $urlLinkForFlexPaginator = $this->urlForHelper();
        $this->controller->set('flexUrl', $urlLinkForFlexPaginator);

        if (!empty($this->_listCandidates)) {
            $this->controller->set('listCandidates', $this->_listCandidates);
        }
        return parent::paginate($object, $this->_defaultConfig);
    }

    /**
     * setListCandidates validate listCandidates.
     *
     * @param [type] $params [description]
     */
    private function setListCandidates($params)
    {
        if (!is_array($params)) {
            throw new \Exception('listCandidates must be array');
        }
        foreach ($params as $value) {
            if (!is_int($value)) {
                throw new \Exception('listCandidates must be the array of integer');
            }
        }

        $this->_listCandidates = $params;

        return;
    }

    /**
     * urlForHelper create Urls For Helper.
     *
     * @return [array] Url
     */
    private function urlForHelper()
    {
        // requestからURL情報を取得
        $urlArray = $this->controller->request->params;
        // pass情報をURLにセットする形に修正
        $urlArray = array_merge($urlArray, $urlArray['pass']);
        // query情報をセット
        $urlArray = array_merge($urlArray, $this->request->query);
        // 不要な情報をunset
        unset($urlArray['_matchedRoute']);
        unset($urlArray['_ext']);
        unset($urlArray['isAjax']);
        unset($urlArray['pass']);
        // page情報は引き継がない
        unset($urlArray['page']);
        return $urlArray;
    }
}
