<?php

namespace Snowdog\DevTest\Controller;

use GateSoftware\SitemapImporter\Importer;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

class SitemapUploadAction
{
    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var PageManager
     */
    private $pageManager;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Importer
     */
    private $importer;

    public function __construct(WebsiteManager $websiteManager, UserManager $userManager, PageManager $pageManager, Importer $importer)
    {
        $this->websiteManager = $websiteManager;
        $this->userManager = $userManager;
        $this->pageManager = $pageManager;
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
    }

    public function execute()
    {
        if (!isset($_SESSION['login'])) {
            header('Location: /login');
            exit;
        }
        foreach ($this->importer->parse($_FILES["sitemapFile"]["tmp_name"]) as $page) {
            if ($websiteIds = $this->websiteManager->getIdByHostname($page['host'])) {
                $websiteId = $websiteIds['website_id'];
            } else {
                $websiteId = $this->websiteManager->create($this->user, $page['host'], $page['host']);
            }
            $website = $this->websiteManager->getById($websiteId);
            $this->pageManager->create($website, $page['page']);
        }
        $_SESSION['flash'] = 'Sitemap imported successfully!';
        header('Location: /sitemap-upload');
    }
}
