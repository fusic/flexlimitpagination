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
     * @return [string] Url
     */
    private function urlForHelper()
    {
        $url = Router::url(null, true);

        $query = $this->request->query;

        if (!empty($query['limit'])) {
            $this->controller->set('currentLimit', $query['limit']);
            unset($query['limit']);
        }

        $isFirstIterate = false;
        foreach ($query as $key => $value) {
            if (!$isFirstIterate) {
                if (is_array($value)) {
                    foreach ($value as $key => $value) {
                        if (!$isFirstIterate) {
                            $url .= '?'.$value.'='.$key.'.'.$value;
                            $isFirstIterate = true;
                        } else {
                            $url .= '&'.$value.'='.$key.'.'.$value;
                        }
                    }
                } else {
                    $url .= '?'.$key.'='.$value;
                    $isFirstIterate = true;
                }
            } else {
                if (is_array($value)) {
                    foreach ($value as $key => $value) {
                        $url .= '&'.$value.'='.$key.'.'.$value;
                    }
                } else {
                    $url .= '&'.$key.'='.$value;
                }
            }
        }


        return $url;
    }
}
