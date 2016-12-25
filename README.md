# Flexpager plugin for CakePHP


## Installation

```
composer require fusic/Flexpager
```

## discription

this plugin will help you to create the candidates of pagination.
ex)

normal paginator

< prev  1 2 3 4 5 next >

this paginator

< prev 1 2 3 4 5 next> 10 20 100

the "10","20","100" means that the use of your application can change the paginate limit flexibly.

## how to use

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
