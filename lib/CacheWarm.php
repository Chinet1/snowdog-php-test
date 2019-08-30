<?php

interface Old_Legacy_CacheWarmer_Resolver_Interface
{
    public function getIp($hostname);
}

class Old_Legacy_CacheWarmer_Resolver_Method implements Old_Legacy_CacheWarmer_Resolver_Interface 
{
    public function getIp($hostname)
    {
        return gethostbyname($hostname);
    }
}

class Old_Legacy_CacheWarmer_Actor
{
    private $callable;

    public function setActor($callable) {
        $this->callable = $callable;
    }
    
    public function act($hostname, $ip, $url)
    {
        call_user_func($this->callable, $hostname, $ip, $url);
    }
}

class Old_Legacy_CacheWarmer_Warmer
{
    /** @var Old_Legacy_CacheWarmer_Actor */
    private $actor;
    /** @var Old_Legacy_CacheWarmer_Resolver_Interface */
    private $resolver;
    /** @var string */
    private $hostname;

    /**
     * @param Old_Legacy_CacheWarmer_Actor $actor
     */
    public function setActor($actor)
    {
        $this->actor = $actor;
    }

    /**
     * @param string $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    public function updateLastVisited($hostname, $url)
    {
        $db = new Snowdog\DevTest\Core\Database();
        $website_id = $db->query('SELECT `website_id` FROM `websites` WHERE `hostname`= "' . $hostname . '"');
        $page_id = $db->query('SELECT `page_id` FROM `pages` WHERE `url` = "' . $url . '" AND `website_id`= "' .
            $website_id->fetch()['website_id'] . '"');
        $date = date('Y-m-d H:i:s');
        $update = $db->prepare('UPDATE `pages` SET `last_visited` = ? WHERE `page_id` = ?');
        $update->execute(array($date, $page_id->fetchColumn(0)));
    }

    /**
     * @param Old_Legacy_CacheWarmer_Resolver_Interface $resolver
     */
    public function setResolver($resolver)
    {
        $this->resolver = $resolver;
    }

    public function warm($url) {
        $ip = $this->resolver->getIp($this->hostname);
        sleep(1); // this emulates visit to http://$hostname/$url via $ip
        $this->actor->act($this->hostname, $ip, $url);
        $this->updateLastVisited($this->hostname, $url);
    }
}

