<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;

class IndexAction
{
    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * @var User
     */
    private $user;

    /**
     * @var int
     */
    private $pagesTotal;

    /**
     * @var string
     */
    private $leastRecentlyVisitedPage;

    /**
     * @var string
     */
    private $mostRecentlyVisitedPage;

    public function __construct(UserManager $userManager, WebsiteManager $websiteManager, PageManager $pageManager)
    {
        $this->websiteManager = $websiteManager;
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
            $this->pagesTotal = $pageManager->getPagesTotalNumber();
            $this->leastRecentlyVisitedPage = $pageManager->getLeastRecentlyVisitedPage();
            $this->mostRecentlyVisitedPage = $pageManager->getMostRecentlyVisitedPage();
        }
    }

    protected function getWebsites()
    {
        if($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        } 
        return [];
    }

    public function execute()
    {
        if (!$this->user) {
            header('Location: /login');
            return;
        }
        require __DIR__ . '/../view/index.phtml';
    }
}