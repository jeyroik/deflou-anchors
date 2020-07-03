<?php
namespace tests\anchors;

use deflou\components\anchors\Anchor;
use deflou\components\anchors\THasAnchor;
use deflou\components\anchors\THasJsonRpcAnchor;
use deflou\components\applications\ApplicationSample;
use deflou\interfaces\anchors\IHasAnchor;
use Dotenv\Dotenv;
use extas\components\http\TSnuffHttp;
use extas\components\Item;
use extas\components\loggers\TSnuffLogging;
use extas\components\THasMagicClass;
use extas\interfaces\http\IHasPsrRequest;
use PHPUnit\Framework\TestCase;

/**
 * Class TraitTest
 *
 * @package tests\anchors
 * @author jeyroik <jeyroik@gmail.com>
 */
class TraitTest extends TestCase
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
            ['anchors', 'name', ApplicationSample::class]
        ]);

        $this->getMagicClass('anchors')->create(new Anchor([
            Anchor::FIELD__ID => 'test',
            Anchor::FIELD__CALLS_NUMBER => 10
        ]));
    }

    public function tearDown(): void
    {
        $this->deleteSnuffDynamicRepositories();
        $this->turnSnuffLoggingOff();
    }

    public function testHasAnchor()
    {
        $item = new class () extends Item implements IHasAnchor{
            use THasAnchor;

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };

        $item->setAnchorId('test');
        $this->assertNotEmpty($item->getAnchor());
        $this->assertEquals(10, $item->getAnchor()->getCallsNumber());
    }

    public function testHasJsonRpcAnchor()
    {
        $item = $this->getItemWithJsonRpcAnchor('.valid');
        $this->assertNotEmpty($item->getJsonRpcAnchor());

        $item = $this->getItemWithJsonRpcAnchor('.invalid');
        $this->assertEmpty($item->getJsonRpcAnchor());
    }

    /**
     * @param string $requestPrefix
     * @return THasJsonRpcAnchor|Item
     */
    protected function getItemWithJsonRpcAnchor(string $requestPrefix)
    {
        return new class ([
            IHasPsrRequest::FIELD__PSR_REQUEST => $this->getPsrRequest($requestPrefix)
        ]) extends Item {
            use THasJsonRpcAnchor;

            protected function getSubjectForExtension(): string
            {
                return 'test';
            }
        };
    }
}
