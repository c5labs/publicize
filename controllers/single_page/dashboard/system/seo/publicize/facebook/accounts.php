<?php

namespace Concrete\Package\Publicize\Controller\SinglePage\Dashboard\System\Seo\Publicize\Facebook;

use View;
use Core;
use Concrete\Core\Page\Controller\DashboardPageController;

class Accounts extends DashboardPageController
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

        $provider = $this->manager->get('publicize-facebook');
        
        // Get the pages that the user owns.
        $pages = $provider->getUserPages(['fields' => 'category,name,id,perms,picture']);
        
        // Get the user.
        $user = $provider->getResourceOwner();

        $this->set('user', $user);
        $this->set('pages', $pages['data']);

        if (count($pages['data']) === 0) {
            header('Location: '.View::url('/dashboard/system/seo/publicize'));
            exit;
        }
    }

    public function save()
    {
        if ($this->token->validate('save', $_POST['ccm_token'])) {
            $data = ['post_as_page' => null];

            if ('me' !== $_POST['object_id']) {
                $data['post_as_page'] = $_POST['object_id'];

                $provider = $this->manager->get('publicize-facebook');
                $page = $provider->getPage($_POST['object_id']);
                $data['connected_as'] = $page['name'];
            }

            $this->manager->getCredentialsStore()->put('publicize-facebook', $data, true);
        }

        header('Location: '.View::url('/dashboard/system/seo/publicize'));

        exit;
    }
}