# Flexpager plugin for CakePHP
maintainer: @gorogoroyasu

## Installation

```
composer require fusic/Flexpager
```

## discription

this plugin will help you to create the candidates of pagination.
ex)

normal paginator
```
< prev  1 2 3 4 5 next >
```
this paginator
```
< prev 1 2 3 4 5 next> 10 20 100
```
the "10","20","100" means that the use of your application can change the paginate limit flexibly.

## settings

first of all, you have to load the component.
and, you have to write "listCandidates" in `public $paginate`

in controller
```

public $paginate = [
    'listCandidates' = [10, 20, 100],
    // and more configs.
];
public function initialize()
{
    parent::initialize();
    $this->loadComponent('Flexpager.Flexpager');
}
```

after that, you have to add this line to AppView.php

```
public function initialize()
{
    parent::initialize();
    $this->loadHelper('Flexpager.Flexpaginator');
}
```

## usage

in controller (for example in index )
```
public function index()
{
    $pages = $this->Flexpager->paginate($this->Pages);
    $this->set(compact('pages'));
}
```

in ctp (for example in index)
```
<?= $this->Flexpaginator->prev('< '.__('previous')) ?>
<?= $this->Flexpaginator->numbers() ?>
<?= $this->Flexpaginator->next(__('next').' >') ?>
<?= $this->Flexpaginator->limitCandidate() ?>
```

the method limitCandidate() will return the list of candidates.

if you want to customize the template,
you can use the method below.

```
<?= $this->Flexpaginator->setFlexPagerTemplate('<a class="form-control" href={{url}}>{{content}}</a>') ?>
```

the args of this method have to include '{{url}}' and '{{content}}'.

the default template is `'<a href={{url}}>{{content}}</a>'`.

please note that `setFlexPagerTemplate` method should appear before the method `listCandidates`.
