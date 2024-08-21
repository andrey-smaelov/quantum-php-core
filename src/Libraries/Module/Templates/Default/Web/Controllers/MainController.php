<?php
use Quantum\Libraries\Module\ModuleManager;
return '<?php

namespace Modules\\' . ModuleManager::$moduleName . '\Controllers;

use Quantum\Factory\ViewFactory;
use Quantum\Mvc\QtController;
use Quantum\Http\Response;

class MainController extends QtController
{
    public function index(Response $response, ViewFactory $view)
    {
        $view->setLayout(\'layouts' . DS . 'main\');
        $view->setParams([
            \'title\' => config()->get(\'app_name\'),
        ]);
        $response->html($view->render(\'index\'));
    }
};';