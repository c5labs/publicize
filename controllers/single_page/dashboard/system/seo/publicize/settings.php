<?php

namespace Concrete\Package\Publicize\Controller\SinglePage\Dashboard\System\Seo\Publicize;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Page\Type\Type as PageType;
use Core;
use View;

class Settings extends DashboardPageController
{
    protected $manager;

    public function __construct(\Concrete\Core\Page\Page $c)
    {
        parent::__construct($c);

        $this->manager = Core::make('authify.manager');
    }

    protected function setPageTypesList()
    {
        $pageTypes = PageType::getList();
        $pageTypesList = [];

        foreach ($pageTypes as $pageType) {
            $pageTypesList[$pageType->getPageTypeId()] = $pageType->getPageTypeName();
        }

        $this->set('page_types', $pageTypesList);
    }

    public function view()
    {
        $this->requireAsset('select2');
        $this->set('form', Core::make('helper/form'));
        $this->setPageTypesList();
        
        $data = $this->manager->getConfigurationStore()->all();

        $settings = [
            'enable_debug_mode', 'enable_automatic_publishing', 
            'enable_logging', 'selected_page_types'
        ];

        foreach ($settings as $settingsKey) {
            if (!empty($data[$settingsKey])) {
                $this->set($settingsKey, $data[$settingsKey]);
            }
        }
    }

    public function save()
    {
        $this->requireAsset('select2');

        if ($this->token->validate('save', $_POST['ccm_token'])) {
            $this->manager->getConfigurationStore()->put('enable_debug_mode', $_POST['enable_debug_mode']);
            $this->manager->getConfigurationStore()->put('enable_logging', $_POST['enable_logging']);
            $this->manager->getConfigurationStore()->put('enable_automatic_publishing', $_POST['enable_automatic_publishing']);
            $this->manager->getConfigurationStore()->put('selected_page_types', $_POST['selected_page_types']);
        }

        header('Location: '. View::url('/dashboard/system/seo/publicize'));

        exit;
    }
}