<?php
/**
 *
 * Date: 26/05/11
 * @author alinares
 */
require_once (__DIR__ . '/../../Silex/autoload.php');

require_once __DIR__ . '/../src/ConfigProvider/Model/Config.php';
require_once __DIR__ . '/../src/ConfigProvider/Silex/Provider/ConfigProvider.php';

class AppConfigTest extends \Silex\WebTestCase
{

	/**
	 * Creates the application.
	 *
	 * @return Symfony\Component\HttpKernel\HttpKernel
	 */
	public function createApplication()
	{
		$app = new \Silex\Application();
		return $app;
	}

	public function testRegisterValues()
	{
		$app = $this->createApplication();
		$app->register(new \ConfigProvider\Silex\Provider\ConfigProvider(), array(
			'config.path' => array(
				'test' => __DIR__ . '/data/test.ini',
				'db' => __DIR__ . '/data/db.ini'
			),
		));

		$app['config']['test']->registerSection($app, 'sectionA');

		$this->assertSame(
			'test',
			$app['sectionA.value1'],
			'The keys are registered in the app'
		);

		$app['config']['db']->registerSection($app, 'sectionA');

		$this->assertSame(
			'testdb',
			$app['sectionA.value1'],
			'The keys from db config are registered in the app'
		);
	}
}
