<?php

namespace Slackbot\Tests;

use PHPUnit\Framework\TestCase;
use Slackbot\Action;

/**
 * Class ActionTest.
 */
class ActionTest extends TestCase
{
    public function testLoad()
    {
        $info = '{"name":"Springfield, MA, USA","type":"button","value":"Springfield, MA, USA"}';
        $action = new Action();
        $action->load($info);

        $this->assertEquals('Springfield, MA, USA', $action->getName());
        $this->assertEquals('button', $action->getType());
        $this->assertEquals('Springfield, MA, USA', $action->getValue());
        $this->assertEmpty($action->getText());
    }

    public function testGetText()
    {
        $action = new Action();
        $action->setText('blah blah');

        $this->assertEquals('blah blah', $action->getText());
    }
}
