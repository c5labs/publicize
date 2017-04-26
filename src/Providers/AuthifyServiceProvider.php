<?php
/**
 * Demo Helper Service Provider File.
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  See attached license file
 */
namespace Concrete\Package\Publicize\Src\Providers;

use BoxedCode\Authify\Manager;
use BoxedCode\Authify\Providers\Manager as ProviderManager;
use BoxedCode\Authify\Stores\ConcreteConfigStore;
use BoxedCode\Authify\Stores\SessionStore;
use Concrete\Core\Foundation\Service\Provider;
use Core;
use Events;
use View;
use Database;
use Log;
use URL;

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * Demo Helper Service Provider.
 */
class AuthifyServiceProvider extends Provider
{
    public function register()
    {
        $configurationRepository = Core::make(
            \Concrete\Core\Config\Repository\Repository::class
        );

        $this->app->singleton('authify.factory', function () {
            $sessionStore = new SessionStore();
            return new ProviderManager($sessionStore);
        });

        $this->app->singleton('authify.configuration', function () use ($configurationRepository) {
            return new ConcreteConfigStore(
                'concrete.authify.configuration', $configurationRepository
            );
        });

        $this->app->singleton('authify.manager', function () use ($configurationRepository) {
            $credentialsStore = new ConcreteConfigStore(
                'concrete.authify.credentials', $configurationRepository
            );

            return new Manager(
                $this->app->make('authify.configuration'),
                $credentialsStore,
                $this->app->make('authify.factory')
            );
        });
        
        Events::addListener('on_page_version_approve', function($event) {
            $db = Database::connection();
            $manager = $this->app->make('authify.manager');
            $config = $manager->getConfigurationStore()->all();
            $page = $event->getPageObject();

            // Ascertain whether we should force republish a page.
            $shouldRepublish = $page->getAttribute('publicize_republish_page') ? true : false;
            
            // Ascertain whether the page has already been published.
            $isAlreadyPublished = $db->fetchColumn(
                'SELECT pID FROM PublicizePublishedPages WHERE pID = ?', 
                [$page->getCollectionID()]
            ) ? true : false;

            // Only share if automatic publishing is enabled & the page is not already published 
            // or we are force republishing it via the republish_page attribute.
            if (isset($config['enable_automatic_publishing']) && !$isAlreadyPublished 
                || $isAlreadyPublished && $shouldRepublish) {

                // Only share if the page is one of the selected types or we are set to publish for all page types.
                if (!isset($config['selected_page_types']) || is_array($config['selected_page_types']) 
                    && in_array($page->getPageTypeID(), $config['selected_page_types'])) {

                    // Prepare the message & link to be shared.
                    $message = $page->getCollectionName();
                    $link = (string) URL::to($page);

                    if (!isset($config['enable_debug_mode'])) {

                        try {
                            // Publish to Twitter
                            if ($manager->has('publicize-twitter')) {
                                $provider = $manager->get('publicize-twitter');
                                $provider->postStatus($message.' '.$link);
                                
                                if (isset($config['enable_logging'])) {
                                    Log::addEntry('Shared to Twitter: '.$message.' '.$link);
                                }
                            }

                            // Publish to Facebook
                            if ($manager->has('publicize-facebook')) {
                                $provider = $manager->get('publicize-facebook');
                                $data = $manager->getCredentialsStore()->get('publicize-facebook');

                                if (isset($data['post_as_page'])) {
                                    $provider->postStatusAsPage($data['post_as_page'], $message, ['link' => $link]);
                                } else {
                                    $provider->postStatus('me', $message, ['link' => $link]);
                                }

                                if (isset($config['enable_logging'])) {
                                    Log::addEntry('Shared to Facebook: '.$message.' '.$link);
                                }
                            }
                        } catch (Exception $ex) {
                            Log::addEntry($ex->getMessage());
                            return;
                        }

                    } else {
                        Log::addEntry('Publicize debug mode, sharing: '.$message.' '.$link);
                    }

                    // Remove republished marker
                    $page->setAttribute('publicize_republish_page', false);

                    // Add page to publicized published pages table.
                    if (!$isAlreadyPublished) {
                        $db->insert('PublicizePublishedPages', ['pID' => $page->getCollectionID()]);
                    }
                }
            }
        });

    }

    public function boot()
    {
        // Code included here will be executed after all service providers have been 
        // registered and the CMS is booting.
    }
}
