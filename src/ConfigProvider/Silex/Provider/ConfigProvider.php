<?php
/**
 *
 * Date: 12/04/11
 * @author alinares
 */
namespace ConfigProvider\Silex\Provider;

use \Silex\ServiceProviderInterface;
use \ConfigProvider\Model\Config;

/**
 * Config silex extension
 */
class ConfigProvider implements ServiceProviderInterface
{

    function register(\Silex\Application $app)
    {
        if (isset($app['config.class_path']))
        {
            /** @var $loader \Symfony\Component\ClassLoader\UniversalClassLoader */
            $loader = $app['autoloader'];
            $class_path = $app['config.class_path'];
            $loader->registerNamespace('ConfigProvider', $class_path);
        }

		if (!is_array($app['config.path'])) {
			$app['config.path'] = array('config' => $app['config.path']);
		}

	    $res = array();
	    foreach($app['config.path'] as $key => $path) {
		    $res[$key] = new Config(
			    $path,
			    isset($app['config.replacements']) ? $app['config.replacements'] : array()
		    );
	    }
	    $app['config'] = $res;
    }
}
