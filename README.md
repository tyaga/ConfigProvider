ConfigExtension
===============

Silex extension to read php .ini files used for configurations.

## Usage

Here is an example of its usage.

We have these .ini files:

paths.ini:

    [doctrine]
    dbal.class_path = "%basepath%/vendor/doctrine/lib/vendor/doctrine-dbal/lib/"
    common.class_path = "%basepath%/vendor/doctrine/lib/vendor/doctrine-common/lib/"
    orm.class_path = "%basepath%/vendor/doctrine/lib/"

db.ini:

    [db]
    options.driver  = "pdo_pgsql"
    options.dbname  = "name"
    options.host    = "localhost"
    options.user    = "myuser"
    options.password = "mysecretpass"

And the index.php:

    <?php
    require 'phar://silex.phar/autoload.php';

    $app = new Silex\Application();

    // configure the autoloader to find the extension classes
    $app['autoloader']->registerNamespace('ConfigExtension', __DIR__.'/../vendor/ConfigExtension/src');
    $app['autoloader']->register();

    $app->register(new \ConfigExtension\Extension\ConfigExtension(), array(
        // specify the .ini file to read
        'config.path' =>  array('paths' => __DIR__ . '/../config/paths.ini', 'db'=>'/../config/db.ini'),
        // and the var replacements
        'config.replacements' => array('basepath' => __DIR__ )
    ));

    // retrieve just one value form the config file
    $db_name = $app['config']['db']->get('options.dbname');

    // adds all the specified section to the silex application
    $app['config']['db']->registerSection($app, 'db');

    // $app['db.options.driver'] now has pdo_pgsql

    $app->get('/', function () use ($app)
    {
        return var_export($app['config']['paths']->getSection('doctrine'), true);
    });
    
    $app->run();

Visiting '/' shows:

    array (
      'doctrine.dbal.class_path' => '/Users/alinares/Sites/test/vendor/doctrine-dbal/lib/',
      'doctrine.common.class_path' => '/Users/alinares/Sites/test/vendor/doctrine/lib/vendor/doctrine-common/lib/',
      'doctrine.orm.class_path' => '/Users/alinares/Sites/test/vendor/doctrine/lib/',
    )
