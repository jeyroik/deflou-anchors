<?php
namespace tests\anchors;

use deflou\components\anchors\Anchor;
use deflou\components\applications\activities\Activity;
use deflou\components\applications\Application;
use deflou\components\plugins\events\EventAppDetermineByAnchor;
use deflou\components\plugins\events\EventDetermineByAnchor;

use extas\components\http\TSnuffHttp;
use extas\components\loggers\TSnuffLogging;
use extas\components\THasMagicClass;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

/**
 * Class PluginsTest
 *
 * @package tests\anchors
 * @author jeyroik <jeyroik@gmail.com>
 */
class PluginsTest extends TestCase
{
    use TSnuffHttp;
    use TSnuffLogging;
    use THasMagicClass;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();
        $this->turnSnuffLoggingOn();
        $this->createSnuffDynamicRepositories([
            ['anchors', 'name', Anchor::class],
            ['applications', 'name', Application::class],
            ['activities', 'name', Activity::class]
        ]);

        $this->getMagicClass('anchors')->create(new Anchor([
            Anchor::FIELD__ID => 'test',
            Anchor::FIELD__CALLS_NUMBER => 10,
            Anchor::FIELD__EVENT_NAME => 'test_event'
        ]));

        $this->getMagicClass('activities')->create(new Activity([
            Activity::FIELD__NAME => 'test_event',
            Activity::FIELD__TYPE => 'event',
            Activity::FIELD__APPLICATION_NAME => 'test_app'
        ]));

        $this->getMagicClass('applications')->create(new Application([
            Application::FIELD__NAME => 'test_app'
        ]));
    }

    public function tearDown(): void
    {
        $this->deleteSnuffDynamicRepositories();
        $this->turnSnuffLoggingOff();
    }

    public function testAppDetermine()
    {
        $plugin = new EventAppDetermineByAnchor([
            EventAppDetermineByAnchor::FIELD__PSR_REQUEST => $this->getPsrRequest('.valid')
        ]);

        $app = null;
        $plugin($app);
        $this->assertNotEmpty($app);

        $plugin = new EventAppDetermineByAnchor([
            EventAppDetermineByAnchor::FIELD__PSR_REQUEST => $this->getPsrRequest('.invalid')
        ]);

        $app = null;
        $plugin($app);
        $this->assertEmpty($app);
    }

    public function testEventDetermine()
    {
        $plugin = new EventDetermineByAnchor([
            EventAppDetermineByAnchor::FIELD__PSR_REQUEST => $this->getPsrRequest('.valid')
        ]);

        $event = null;
        $app = new Application();
        $plugin($event, $app);
        $this->assertNotEmpty($event);

        $plugin = new EventDetermineByAnchor([
            EventAppDetermineByAnchor::FIELD__PSR_REQUEST => $this->getPsrRequest('.invalid')
        ]);

        $event = null;
        $app = new Application();
        $plugin($event, $app);
        $this->assertEmpty($event);
    }
}
