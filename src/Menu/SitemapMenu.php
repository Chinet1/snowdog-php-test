<?php


namespace Snowdog\DevTest\Menu;


class SitemapMenu extends AbstractMenu
{
    public function isActive()
    {
        return $_SERVER['REQUEST_URI'] == '/sitemap-upload';
    }

    public function getHref()
    {
        return '/sitemap-upload';
    }

    public function getLabel()
    {
        return 'Sitemap Upload';
    }
}
