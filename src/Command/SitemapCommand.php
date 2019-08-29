<?php

namespace Snowdog\DevTest\Command;

use GateSoftware\SitemapImporter\Importer;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Symfony\Component\Console\Output\OutputInterface;

class SitemapCommand
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
     * @var Importer
     */
    private $importer;

    public function __construct(WebsiteManager $websiteManager, UserManager $userManager, PageManager $pageManager, Importer $importer)
    {
        $this->websiteManager = $websiteManager;
        $this->userManager = $userManager;
        $this->pageManager = $pageManager;
        $this->importer = $importer;
    }

    public function __invoke($file, $userLogin, OutputInterface $output)
    {
        $user = $this->userManager->getByLogin($userLogin);
        if ($pages = $this->importer->parse($file)) {
            foreach ($pages as $page) {
                if ($websiteIds = $this->websiteManager->getIdByHostname($page['host'])) {
                    $websiteId = $websiteIds['website_id'];
                } else {
                    $websiteId = $this->websiteManager->create($user, $page['host'], $page['host']);
                }
                $website = $this->websiteManager->getById($websiteId);
                $this->pageManager->create($website, $page['page']);
            }
            $output->writeln('Sitemap imported successfully!');
        } else {
            $output->writeln('<error>Wrong file!</error>');
        }
    }
}