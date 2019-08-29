<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class PageManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(Database $database, UserManager $userManager)
    {
        $this->database = $database;
        $this->userManager = $userManager;
    }

    public function getAllByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM pages WHERE website_id = :website');
        $query->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }

    public function getPagesTotalNumber()
    {
        $id = $this->userManager->getByLogin($_SESSION['login'])->getUserId();
        $query = 'SELECT COUNT(*) FROM `pages` JOIN `websites` ON `pages`.`website_id` = `websites`.`website_id` WHERE `websites`.`user_id` = "' .
            $id .'"';
        $result = $this->database->query($query)->fetch();
        return $result[0];
    }

    public function getLeastRecentlyVisitedPage()
    {
        $id = $this->userManager->getByLogin($_SESSION['login'])->getUserId();
        $query = 'SELECT CONCAT(`hostname`, "/", `url`) as "page" FROM `pages` JOIN `websites` ON `pages`.`website_id` = `websites`.`website_id` WHERE `websites`.`user_id` = "' .
            $id .'" ORDER BY `last_visited` LIMIT 1';
        $result = $this->database->query($query)->fetch();
        return $result['page'];
    }

    public function getMostRecentlyVisitedPage()
    {
        $id = $this->userManager->getByLogin($_SESSION['login'])->getUserId();
        $query = 'SELECT CONCAT(`hostname`, "/", `url`) as "page" FROM `pages` JOIN `websites` ON `pages`.`website_id` = `websites`.`website_id` WHERE `websites`.`user_id` = "' .
            $id .'" ORDER BY `last_visited` DESC LIMIT 1';
        $result = $this->database->query($query)->fetch();
        return $result['page'];
    }

    public function create(Website $website, $url)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO pages (url, website_id) VALUES (:url, :website)');
        $statement->bindParam(':url', $url, \PDO::PARAM_STR);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }
}