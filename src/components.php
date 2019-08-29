<?php

use Snowdog\DevTest\Command\MigrateCommand;
use Snowdog\DevTest\Command\SitemapCommand;
use Snowdog\DevTest\Command\WarmCommand;
use Snowdog\DevTest\Component\CommandRepository;
use Snowdog\DevTest\Component\Menu;
use Snowdog\DevTest\Component\Migrations;
use Snowdog\DevTest\Component\RouteRepository;
use Snowdog\DevTest\Controller\CreatePageAction;
use Snowdog\DevTest\Controller\CreateVarnishAction;
use Snowdog\DevTest\Controller\CreateVarnishLinkAction;
use Snowdog\DevTest\Controller\CreateWebsiteAction;
use Snowdog\DevTest\Controller\IndexAction;
use Snowdog\DevTest\Controller\LoginAction;
use Snowdog\DevTest\Controller\LoginFormAction;
use Snowdog\DevTest\Controller\LogoutAction;
use Snowdog\DevTest\Controller\RegisterAction;
use Snowdog\DevTest\Controller\RegisterFormAction;
use Snowdog\DevTest\Controller\SitemapUploadAction;
use Snowdog\DevTest\Controller\SitemapUploaderFormAction;
use Snowdog\DevTest\Controller\VarnishesAction;
use Snowdog\DevTest\Controller\WebsiteAction;
use Snowdog\DevTest\Menu\LoginMenu;
use Snowdog\DevTest\Menu\RegisterMenu;
use Snowdog\DevTest\Menu\SitemapMenu;
use Snowdog\DevTest\Menu\VarnishesMenu;
use Snowdog\DevTest\Menu\WebsitesMenu;

RouteRepository::registerRoute('GET', '/', IndexAction::class, 'execute', RouteRepository::ACCESS_TYPE_LOGGED);
RouteRepository::registerRoute('GET', '/login', LoginFormAction::class, 'execute', RouteRepository::ACCESS_TYPE_GUEST);
RouteRepository::registerRoute('POST', '/login', LoginAction::class, 'execute', RouteRepository::ACCESS_TYPE_GUEST);
RouteRepository::registerRoute('GET', '/logout', LogoutAction::class, 'execute', RouteRepository::ACCESS_TYPE_LOGGED);
RouteRepository::registerRoute('GET', '/register', RegisterFormAction::class, 'execute', RouteRepository::ACCESS_TYPE_GUEST);
RouteRepository::registerRoute('POST', '/register', RegisterAction::class, 'execute', RouteRepository::ACCESS_TYPE_GUEST);
RouteRepository::registerRoute('GET', '/website/{id:\d+}', WebsiteAction::class, 'execute', RouteRepository::ACCESS_TYPE_LOGGED);
RouteRepository::registerRoute('POST', '/website', CreateWebsiteAction::class, 'execute', RouteRepository::ACCESS_TYPE_LOGGED);
RouteRepository::registerRoute('POST', '/page', CreatePageAction::class, 'execute', RouteRepository::ACCESS_TYPE_LOGGED);
RouteRepository::registerRoute('GET', '/varnishes', VarnishesAction::class, 'execute', RouteRepository::ACCESS_TYPE_LOGGED);
RouteRepository::registerRoute('POST', '/varnish', CreateVarnishAction::class, 'execute', RouteRepository::ACCESS_TYPE_LOGGED);
RouteRepository::registerRoute('POST', '/varnishlink', CreateVarnishLinkAction::class, 'execute', RouteRepository::ACCESS_TYPE_LOGGED);
RouteRepository::registerRoute('POST', '/varnishunlink', CreateVarnishLinkAction::class, 'executeUnlink', RouteRepository::ACCESS_TYPE_LOGGED);
RouteRepository::registerRoute('GET', '/sitemap-upload', SitemapUploaderFormAction::class, 'execute', RouteRepository::ACCESS_TYPE_LOGGED);
RouteRepository::registerRoute('POST', '/sitemap-upload', SitemapUploadAction::class, 'execute', RouteRepository::ACCESS_TYPE_LOGGED);

CommandRepository::registerCommand('migrate_db', MigrateCommand::class);
CommandRepository::registerCommand('warm [id]', WarmCommand::class);
CommandRepository::registerCommand('sitemap [file] [userLogin]', SitemapCommand::class);

Menu::register(LoginMenu::class, 200);
Menu::register(RegisterMenu::class, 250);
Menu::register(WebsitesMenu::class, 10);
Menu::register(VarnishesMenu::class, 20);
Menu::register(SitemapMenu::class, 30);

Migrations::registerComponentMigration('Snowdog\\DevTest', 3);