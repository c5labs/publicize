<?php

namespace Concrete\Package\Publicize\Controller\SinglePage\Dashboard\System\Seo\Publicize;

use View;
use Core;
use Concrete\Core\Page\Controller\DashboardPageController;

class Twitter extends DashboardPageController
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
        $this->set('publicize_twitter', $this->manager->getConfigurationStore()->get('twitter'));
    }

    public function authorize()
    {
        if ($this->token->validate('authorize', $_POST['ccm_token'])) {
            $data = (array) $_POST['publicize_twitter'];
            $data['callback_uri'] = View::url('/dashboard/system/seo/publicize/twitter/exchange');
            $this->manager->getConfigurationStore()->put('twitter', $data);
            
            $this->manager->make('twitter', 'publicize-twitter')->authorize();
        }
    }

    public function exchange()
    {
        $provider = $this->manager->make('twitter', 'publicize-twitter');
        $provider->exchange($_GET);
        $user = $provider->getUserDetails();
        $this->manager->save($provider);
        $data = ['connected_as' => $user->name . ' (@'.$user->nickname.')'];
        $this->manager->getCredentialsStore()->put('publicize-twitter', $data, true);

        header('Location: '. View::url('/dashboard/system/seo/publicize'));
        exit;
    }
}