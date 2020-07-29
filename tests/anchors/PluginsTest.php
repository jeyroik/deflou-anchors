<?php
namespace tests\anchors;

use deflou\components\anchors\Anchor;
use deflou\components\applications\activities\Activity;
use deflou\components\applications\Application;
use deflou\components\plugins\events\AppDetermineByAnchor;
use deflou\components\plugins\events\EventDetermineByAnchor;

use deflou\components\plugins\triggers\ValidateTriggerByAnchor;
use deflou\components\servers\requests\ApplicationRequest;
use deflou\components\triggers\events\ApplicationEvent;
use deflou\components\triggers\Trigger;
use deflou\interfaces\anchors\IHasAnchorData;
use extas\components\loggers\TSnuffLogging;

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
    use TSnuffLogging;

    protected function setUp(): void
    {
        parent::setUp();
        $env = Dotenv::create(getcwd() . '/tests/');
        $env->load();

        $this->turnSnuffLoggingOn([
            ['anchors', 'name', Anchor::class],
            ['applications', 'name', Application::class],
            ['activities', 'name', Activity::class]
        ]);

        $this->getMagicClass('anchors')->create(new Anchor([
            Anchor::FIELD__ID => 'test',
            Anchor::FIELD__CALLS_NUMBER => 10,
            Anchor::FIELD__EVENT_NAME => 'test_event',
            Anchor::FIELD__TYPE => Anchor::TYPE__GENERAL
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
        $plugin = new AppDetermineByAnchor();

        $appRequest = new ApplicationRequest();
        $appRequest->addParameterByValue($appRequest::PARAM__DATA, [
            IHasAnchorData::FIELD__ANCHOR => 'test_anchor'
        ]);
        $appRequest  = $plugin($appRequest);
        $this->assertNotEmpty(
            $appRequest->getParameterValue($appRequest::PARAM__EVENT_APPLICATION),
            'App is not determined'
        );

        $appRequest = new ApplicationRequest();
        $appRequest = $plugin($appRequest);

        $this->assertEmpty(
            $appRequest->getParameterValue($appRequest::PARAM__EVENT_APPLICATION),
            'Application determined: ' . print_r($appRequest, true)
        );
    }

    public function testEventDetermine()
    {
        $plugin = new EventDetermineByAnchor();

        $appRequest = new ApplicationRequest();
        $appRequest->addParameterByValue($appRequest::PARAM__DATA, [
            IHasAnchorData::FIELD__ANCHOR => 'test_anchor'
        ]);
        
        $appRequest = $plugin($appRequest);
        $this->assertNotEmpty(
            $appRequest->getParameterValue($appRequest::PARAM__EVENT),
            'Event is not determined'
        );

        $appRequest = new ApplicationRequest();
        $appRequest = $plugin($appRequest);

        $this->assertEmpty(
            $appRequest->getParameterValue($appRequest::PARAM__EVENT),
            'Event determined: ' . print_r($appRequest, true)
        );
    }

    public function testValidateTrigger()
    {
        $plugin = new ValidateTriggerByAnchor();
        $event = new ApplicationEvent();
        $trigger = new Trigger([
            Trigger::FIELD__EVENT_NAME => 'test_event',
            Trigger::FIELD__NAME => 'test'
        ]);

        $valid = $plugin($event, $trigger);
        $this->assertFalse($valid, 'Incorrect trigger validation');

        $event->addParameterByValue($event::PARAM__ARTIFACTS, [IHasAnchorData::FIELD__ANCHOR => 'test']);

        $valid = $plugin($event, $trigger);
        $this->assertTrue($valid, 'Invalid trigger');

        $this->getMagicClass('anchors')->create(new Anchor([
            Anchor::FIELD__TYPE => Anchor::TYPE__TRIGGER,
            Anchor::FIELD__ID => 'trigger_type',
            Anchor::FIELD__TRIGGER_NAME => 'test'
        ]));

        $event = new ApplicationEvent();
        $event->addParameterByValue(
            $event::PARAM__ARTIFACTS,
            [IHasAnchorData::FIELD__ANCHOR => 'trigger_type']
        );

        $valid = $plugin($event, $trigger);
        $this->assertTrue($valid, 'Invalid trigger');

        $this->getMagicClass('anchors')->create(new Anchor([
            Anchor::FIELD__TYPE => Anchor::TYPE__PLAYER,
            Anchor::FIELD__ID => 'player_type',
            Anchor::FIELD__TRIGGER_NAME => 'test'
        ]));

        $event = new ApplicationEvent();
        $event->addParameterByValue(
            $event::PARAM__ARTIFACTS,
            [IHasAnchorData::FIELD__ANCHOR => 'player_type']
        );

        $valid = $plugin($event, $trigger);
        $this->assertTrue($valid, 'Invalid trigger');
    }
}
