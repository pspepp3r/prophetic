<?php

declare(strict_types=1);

use DI\Container;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use ipinfo\ipinfo\IPinfo;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use Src\Classes\Csrf;
use Src\Classes\EntrypointLookupCollection as ClassesEntrypointLookupCollection;
use Src\Classes\RouteEntityBindingStrategy;
use Src\Contracts\RequestValidatorFactoryInterface;
use Src\Services\ConfigService;
use Src\Validators\RequestValidatorFactory;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\BodyRendererInterface;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookup;
use Symfony\WebpackEncoreBundle\Asset\TagRenderer;
use Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension;
use Twig\Extra\Intl\IntlExtension;

return [

    App::class => function (Container $container) {

        AppFactory::setContainer($container);

        $router = require CONFIG_PATH . '/routes/web.php';
        $addMiddleware = require CONFIG_PATH . '/middleware.php';

        $app = AppFactory::create();

        $app->getRouteCollector()->setDefaultInvocationStrategy(
            new RouteEntityBindingStrategy(
                $container->get(EntityManager::class),
                $app->getResponseFactory()
            )
        );

        $router($app);
        $addMiddleware($app);

        return $app;
    },

    BodyRendererInterface::class            => fn(Twig $twig) => new BodyRenderer($twig->getEnvironment()),

    ConfigService::class => new ConfigService(require CONFIG_PATH . '/app.php'),

    'csrf'                                  => fn(ResponseFactoryInterface $responseFactory, Csrf $csrf) => new Guard(
        $responseFactory,
        failureHandler: $csrf->failureHandler(),
        persistentTokenMode: true
    ),

    EntityManager::class => function ($connection, $ORMConfig, ConfigService $configService): EntityManager {

        $connection = DriverManager::getConnection($configService->get('db'));

        $ORMConfig = ORMSetup::createAttributeMetadataConfiguration([$configService->get('db.entity_dir')], $configService->get('db.dev_mode'));
        $entityManager = new EntityManager($connection, $ORMConfig);
        return $entityManager;
    },

    IPinfo::class => fn(ConfigService $config) => new IPinfo($config->get('ipinfo.access_token')),

    MailerInterface::class                  => function (ConfigService $config) {
        if ($config->get('mailer.driver') === 'log') {
            return new \Src\Classes\Mailer();
        }

        $transport = Transport::fromDsn($config->get('mailer.dsn'));

        return new Mailer($transport);
    },

    ResponseFactoryInterface::class => fn(App $app) => $app->getResponseFactory(),

    RequestValidatorFactoryInterface::class => fn(Container $container) => $container->get(
        RequestValidatorFactory::class
    ),

    RouteParserInterface::class             => fn(App $app) => $app->getRouteCollector()->getRouteParser(),

    SessionInterface::class => fn(ConfigService $config) => new Session(new NativeSessionStorage([
        'cookie_lifetime' => 2592000,
        'cookie_httponly' => $config->get('session.httponly'),
        'cookie_secure' => $config->get('session.secure'),
        'cookie_samesite' => $config->get('session.samesite')
    ])),


    Twig::class => function (Container $container, ConfigService $configService): Twig {

        $twig = Twig::create(VIEW_PATH, ['cache' => STORAGE_PATH . '/cache/twig', 'auto_reload' => $configService->get('db.dev_mode')]);

        $twig->addExtension(new IntlExtension());
        $twig->addExtension(new EntryFilesTwigExtension($container));
        $twig->addExtension(new AssetExtension($container->get('webpack_encore.packages')));

        return $twig;
    },

    'webpack_encore.entrypoint' => fn() => new EntrypointLookup(BUILD_PATH . '/entrypoints.json'),

    'webpack_encore.packages' => fn() => new Packages(
        new Package(new JsonManifestVersionStrategy(BUILD_PATH . '/manifest.json'))
    ),

    'webpack_encore.tag_renderer' => fn(Container $container) => new TagRenderer(
        new ClassesEntrypointLookupCollection($container, 'webpack_encore.entrypoint'),
        $container->get('webpack_encore.packages')
    ),

];
