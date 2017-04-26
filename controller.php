<?php
/**
 * Publicize Controller File.
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  See attached license file
 */
namespace Concrete\Package\Publicize;

use Concrete\Core\Attribute\Key\CollectionKey;
use Concrete\Core\Attribute\Type;
use Concrete\Core\Foundation\Service\ProviderList;
use Concrete\Core\Package\Package;
use Concrete\Core\Page\Single as SinglePage;
use Core;
use Illuminate\Filesystem\Filesystem;

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * Package Controller Class.
 *
 * Automatically share pages to Facebook & Twitter.
 *
 * @author   Oliver Green <oliver@c5labs.com>
 * @license  See attached license file
 */
class Controller extends Package
{
    /**
     * Minimum version of concrete5 required to use this package.
     * 
     * @var string
     */
    protected $appVersionRequired = '5.7.5';

    /**
     * Does the package provide a full content swap?
     * This feature is often used in theme packages to install 'sample' content on the site.
     *
     * @see https://goo.gl/C4m6BG
     * @var bool
     */
    protected $pkgAllowsFullContentSwap = false;

    /**
     * Does the package provide thumbnails of the files 
     * imported via the full content swap above?
     *
     * @see https://goo.gl/C4m6BG
     * @var bool
     */
    protected $pkgContentProvidesFileThumbnails = false;

    /**
     * Should we remove 'Src' from classes that are contained 
     * ithin the packages 'src/Concrete' directory automatically?
     *
     * '\Concrete\Package\MyPackage\Src\MyNamespace' becomes '\Concrete\Package\MyPackage\MyNamespace'
     *
     * @see https://goo.gl/4wyRtH
     * @var bool
     */
    protected $pkgAutoloaderMapCoreExtensions = false;

    /**
     * Package class autoloader registrations
     * The package install helper class, included with this boilerplate, 
     * is activated by default.
     *
     * @see https://goo.gl/4wyRtH
     * @var array
     */
    protected $pkgAutoloaderRegistries = [
        //'src/MyVendor/Statistics' => '\MyVendor\ConcreteStatistics'
    ];

    /**
     * The packages handle.
     * Note that this must be unique in the 
     * entire concrete5 package ecosystem.
     * 
     * @var string
     */
    protected $pkgHandle = 'publicize';

    /**
     * The packages version.
     * 
     * @var string
     */
    protected $pkgVersion = '0.9.0';

    /**
     * The packages name.
     * 
     * @var string
     */
    protected $pkgName = 'Publicize';

    /**
     * The packages description.
     * 
     * @var string
     */
    protected $pkgDescription = 'Automatically share pages to Facebook & Twitter.';

    /**
     * Package service providers to register.
     * 
     * @var array
     */
    protected $providers = [
        // Register your concrete5 service providers here
        'Concrete\Package\Publicize\Src\Providers\AuthifyServiceProvider',
    ];

    /**
     * Register the packages defined service providers.
     * 
     * @return void
     */
    protected function registerServiceProviders()
    {
        $list = new ProviderList(Core::getFacadeRoot());

        foreach ($this->providers as $provider) {
            $list->registerProvider($provider);

            if (method_exists($provider, 'boot')) {
                Core::make($provider)->boot($this);
            }
        }
    }

    /**
     * Boot the packages composer autoloader if it's present.
     * 
     * @return void
     */
    protected function bootComposer()
    {
        $filesystem = new Filesystem();
        $path = __DIR__.'/vendor/autoload.php';

        if ($filesystem->exists($path)) {
            $filesystem->getRequire($path);
        }
    }

    /**
     * The packages on start hook that is fired as the CMS is booting up.
     * 
     * @return void
     */
    public function on_start()
    {
        // Boot composer
        $this->bootComposer();
        // Register defined service providers
        $this->registerServiceProviders();

        // Add custom logic here that needs to be executed during CMS boot, things
        // such as registering services, assets, etc.
    }

    /**
     * The packages install routine.
     * 
     * @return \Concrete\Core\Package\Package
     */
    public function install()
    {
        // Boot composer
        $this->bootComposer();

        // Add your custom logic here that needs to be executed BEFORE package install.

        $pkg = parent::install();

        // Register defined service providers
        $this->registerServiceProviders();

        // Install settings pages.
        $basePage = SinglePage::add('/dashboard/system/seo/publicize', $pkg);
        $basePage->update(array('cName'=>t('Publicize'), 'cDescription'=>''));

        $settingsPage = SinglePage::add('/dashboard/system/seo/publicize/settings', $pkg);
        $settingsPage->update(array('cName'=>t('Publicize Settings'), 'cDescription'=>''));
        $settingsPage->setAttribute('exclude_nav', true);

        $facebookPage = SinglePage::add('/dashboard/system/seo/publicize/facebook', $pkg);
        $facebookPage->update(array('cName'=>t('Connect Facebook'), 'cDescription'=>''));
        $facebookPage->setAttribute('exclude_nav', true);

        $facebookPage = SinglePage::add('/dashboard/system/seo/publicize/facebook/accounts', $pkg);
        $facebookPage->update(array('cName'=>t('Facebook Accounts'), 'cDescription'=>''));
        $facebookPage->setAttribute('exclude_nav', true);

        $twitterPage = SinglePage::add('/dashboard/system/seo/publicize/twitter', $pkg);
        $twitterPage->update(array('cName'=>t('Connect Twitter'), 'cDescription'=>''));
        $twitterPage->setAttribute('exclude_nav', true);

        // Install re-pubish page attribute
        CollectionKey::add(
            Type::getByHandle('boolean'), 
            array('akHandle' => 'publicize_republish_page', 'akName' => t('Re-publish page via Publicize'), 'akIsSearchable' => false), 
            $pkg
        );

        return $pkg;
    }

    /**
     * The packages upgrade routine.
     * 
     * @return void
     */
    public function upgrade()
    {
        // Add your custom logic here that needs to be executed BEFORE package install.

        parent::upgrade();

        // Add your custom logic here that needs to be executed AFTER package upgrade.
    }

    /**
     * The packages uninstall routine.
     * 
     * @return void
     */
    public function uninstall()
    {
        // Add your custom logic here that needs to be executed BEFORE package uninstall.
        $configurationRepository = Core::make(
            \Concrete\Core\Config\Repository\Repository::class
        );

        $configurationRepository->save('concrete.authify', null);

        parent::uninstall();

        // Add your custom logic here that needs to be executed AFTER package uninstall.
    }
}
