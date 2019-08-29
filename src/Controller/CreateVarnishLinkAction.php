<?php


namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\WebsiteManager;

class CreateVarnishLinkAction
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var VarnishManager
     */
    private $varnishManager;

    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    public function __construct(UserManager $userManager, VarnishManager $varnishManager, WebsiteManager $websiteManager)
    {
        $this->userManager = $userManager;
        $this->varnishManager = $varnishManager;
        $this->websiteManager = $websiteManager;
    }

    public function execute()
    {
        $website_id = $_POST['website'];
        $varnish_id = $_POST['varnish'];
        if (isset($_SESSION['login'])) {
            $website = $this->websiteManager->getById($website_id);
            $varnish = $this->varnishManager->getById($varnish_id);
            $this->varnishManager->link($varnish, $website);
        } else {
            header('Location: /login');
            exit;
        }
    }

    public function executeUnlink()
    {
        $website_id = $_POST['website'];
        $varnish_id = $_POST['varnish'];
        if (isset($_SESSION['login'])) {
            $website = $this->websiteManager->getById($website_id);
            $varnish = $this->varnishManager->getById($varnish_id);
            $this->varnishManager->unlink($varnish, $website);
        } else {
            header('Location: /login');
            exit;
        }
    }
}
