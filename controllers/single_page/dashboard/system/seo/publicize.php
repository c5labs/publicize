<?php

namespace Concrete\Package\Publicize\Controller\SinglePage\Dashboard\System\Seo;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Page\Type\Type as PageType;
use Core;
use View;

class Publicize extends DashboardPageController
{
    protected $manager;

    public function __construct(\Concrete\Core\Page\Page $c)
    {
        parent::__construct($c);

        $this->manager = Core::make('authify.manager');
    }

    public function view() 
    {
        $this->set('form', Core::make('helper/form'));

        if ($this->manager->has('publicize-twitter')) {
            $this->set('twitter', $this->manager->getCredentialsStore()->get('publicize-twitter'));
        }

        if ($this->manager->has('publicize-facebook')) {
            $this->set('facebook', $this->manager->getCredentialsStore()->get('publicize-facebook'));
        }

        $data = $this->manager->getConfigurationStore()->all();

        $settings = [
            'enable_debug_mode', 'enable_automatic_publishing', 
            'enable_logging'
        ];

        foreach ($settings as $settingsKey) {
            if (!empty($data[$settingsKey])) {
                $this->set($settingsKey, $data[$settingsKey]);
            }
        }

        if (!empty($data['selected_page_types'])) {

            $selected_page_types = array_map(function($item) {
                return PageType::getById($item)->getPageTypeName();
            }, $data['selected_page_types']);

            if (count($selected_page_types) > 1) {
                $last_item = array_pop($selected_page_types);
            }

            $selected_page_types = implode(', ', $selected_page_types);

            if (isset($last_item)) {
                $selected_page_types .= ' & '.$last_item;
            }

            $this->set('selected_page_types', $selected_page_types);
        }
    }

    public function disconnect()
    {
        if ($this->token->validate('disconnect', $_POST['ccm_token'])) {
            $handle = $_GET['provider'];

            if ($this->manager->has($handle)) {
                $this->manager->destroy($handle);
            }
        }

        header('Location: '.View::url('/dashboard/system/seo/publicize'));
    }
}