<?php

namespace Concrete\Package\Publicize\Controller\SinglePage\Dashboard\System\Seo\Publicize;

use View;
use Core;
use Concrete\Core\Page\Controller\DashboardPageController;

class Facebook extends DashboardPageController
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
        $this->set('publicize_facebook', $this->manager->getConfigurationStore()->get('facebook'));
    }

    public function authorize()
    {
        if ($this->token->validate('authorize', $_POST['ccm_token'])) {
            $data = (array) $_POST['publicize_facebook'];
            $data['callback_uri'] = View::url('/dashboard/system/seo/publicize/facebook/exchange');
            $this->manager->getConfigurationStore()->put('facebook', $data);
            
            $this->manager->make('facebook', 'publicize-facebook')->authorize(
                ['email', 'publish_actions', 'manage_pages', 'publish_pages']
            );
        }
    }

    public function exchange()
    {
        $provider = $this->manager->make('facebook', 'publicize-facebook');
        $provider->exchange($_GET);
        $user = $provider->getResourceOwner();
        $this->manager->save($provider);
        $data = ['connected_as' => $user->getName()];
        $this->manager->getCredentialsStore()->put('publicize-facebook', $data, true);

        header('Location: '. View::url('/dashboard/system/seo/publicize/facebook/accounts'));
        exit;
    }
}