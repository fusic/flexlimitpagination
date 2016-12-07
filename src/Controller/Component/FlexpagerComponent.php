<?php

namespace Flexpager\Controller\Component;

use Cake\Controller\Component;
use Cake\Routing\Router;

class FlexpagerComponent extends Component
{
    // paginator を呼び出す
    public $components = ['Paginator'];

    // リストの候補 array
    protected $_listCandidates = [];

    // config
    protected $_defaultConfig = [
        'page' => 1,
        'limit' => 10,
        'maxLimit' => 100,
    ];

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

    // call FlexpaginatorHelper
    public function startup($event)
    {
        $event->subject->helpers += [
            'Flexpager.Flexpaginator',
        ];
    }

    public function paginate($object, array $settings = [])
    {
        $query = $this->request->query;
        if (!empty($query['limit']) && is_numeric($query['limit'])) {
            $this->_defaultConfig['limit'] = $query['limit'];
        }
        $results = $this->Paginator->paginate($object, $this->_defaultConfig);
        // リンクを作成
        $urlLinkForFlexPaginator = $this->urlForHelper();
        $this->controller->set('flexUrl', $urlLinkForFlexPaginator);

        if (!empty($this->_listCandidates)) {
            $this->controller->set('listCandidates', $this->_listCandidates);
        }

        return $results;
    }

    // is Array, is Numeric Validation
    private function setListCandidates($params)
    {
        if (!is_array($params)) {
            return;
        }
        foreach ($params as $value) {
            if (!is_numeric($value)) {
                return;
            }
        }

        $this->_listCandidates = $params;

        return;
    }

    private function urlForHelper()
    {
        $url = Router::url(null, true);
        $query = $this->request->query;
        if (!empty($query['limit'])) {
            unset($query['limit']);
        }

        $isFirstIterate = true;
        foreach ($query as $key => $value) {
            if ($isFirstIterate) {
                $url .= '?'.$key.'='.$value;
            } else {
                $url .= '&'.$key.'='.$value;
            }
        }

        return $url;
    }
}
