<?php


namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class VarnishManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getById($varnishId) {
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM varnish WHERE varnish_id = :id');
        $query->setFetchMode(\PDO::FETCH_CLASS, Varnish::class);
        $query->bindParam(':id', $varnishId, \PDO::PARAM_INT);
        $query->execute();
        /** @var Varnish $varnish */
        $varnish = $query->fetch(\PDO::FETCH_CLASS);
        return $varnish;
    }

    public function getAllByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM varnish WHERE user_id = :user');
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Varnish::class);
    }

    public function getWebsites(Varnish $varnish)
    {
        $varnishId = $varnish->getVarnishId();
        $query = $this->database->prepare('SELECT * FROM websites JOIN varnish_website_link ON websites.website_id = varnish_website_link.website_id WHERE varnish_id = :varnish');
        $query->bindParam(':varnish', $varnishId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Website::class);
    }

    public function getByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        $query = $this->database->prepare('SELECT * FROM varnish JOIN varnish_website_link ON varnish.varnish_id = varnish_website_link.varnish_id WHERE website_id = :website');
        $query->setFetchMode(\PDO::FETCH_CLASS, Varnish::class);
        $query->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetch( \PDO::FETCH_CLASS);
    }

    public function create(User $user, $ip)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO `varnish`(`ip`, `user_id`) VALUES (:ip, :user)');
        $statement->bindParam(':ip', $ip, \PDO::PARAM_STR);
        $statement->bindParam(':user', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function link(Varnish $varnish, Website $website)
    {
        $varnishId = $varnish->getVarnishId();
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO `varnish_website_link`(`varnish_id`, `website_id`) VALUES (:varnish, :website)');
        $statement->bindParam(':varnish', $varnishId, \PDO::PARAM_INT);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function unlink(Varnish $varnish, Website $website)
    {
        $varnishId = $varnish->getVarnishId();
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('DELETE FROM `varnish_website_link` WHERE `varnish_id` = :varnish AND `website_id` = :website');
        $statement->bindParam(':varnish', $varnishId, \PDO::PARAM_INT);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }
}
