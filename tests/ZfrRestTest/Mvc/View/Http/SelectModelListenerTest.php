<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace ZfrRestTest\Mvc\View\Http;

use PHPUnit_Framework_TestCase;
use Zend\Console\Request as ConsoleRequest;
use Zend\Http\Header;
use Zend\Http\Request as HttpRequest;
use Zend\Mvc\MvcEvent;
use ZfrRest\Mvc\View\Http\SelectModelListener;

class SelectModelListenerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SelectModelListener
     */
    protected $selectListener;


    /**
     * Data provider #1
     *
     * @return array
     */
    public function typesToModel()
    {
        return array(
            array('text/html', 'Zend\View\Model\ViewModel'),
            array('application/xhtml+xml', 'Zend\View\Model\ViewModel'),
            array('application/javascript', 'Zend\View\Model\ViewModel'),
            array('application/json', 'Zend\View\Model\ViewModel')
        );
    }

    public function setUp()
    {
        $this->selectListener = new SelectModelListener();
    }

    /**
     * @dataProvider typesToModel
     */
    public function testCorrectlySelectModelFromAcceptValue($type, $modelClass)
    {
        $event   = new MvcEvent();
        $request = new HttpRequest();
        $headers = $request->getHeaders();

        $accept  = new Header\Accept();
        $accept->addMediaType($type, 1);
        $headers->addHeader($accept);

        $event->setRequest($request);

        $this->selectListener->selectModel($event);

        $model = $event->getResult();

        $this->assertInstanceOf($modelClass, $model);
    }

    public function testDoesNotSetEventResultIfRequestDoesNotHaveAcceptHeader()
    {
        $event   = new MvcEvent();
        $request = new HttpRequest();
        $event->setRequest($request);

        $this->selectListener->selectModel($event);

        $model = $event->getResult();

        $this->assertNull($model);
    }

    public function testDoesNotSetEventResultIfRequestIsNotHttp()
    {
        $event   = new MvcEvent();
        $request = new ConsoleRequest();

        $event->setRequest($request);

        $this->selectListener->selectModel($event);

        $model = $event->getResult();

        $this->assertNull($model);
    }

    public function testSetVariablesIfModelIsMatched()
    {
        $event   = new MvcEvent();
        $request = new HttpRequest();
        $headers = $request->getHeaders();

        $accept  = new Header\Accept();
        $accept->addMediaType('text/html', 1);
        $headers->addHeader($accept);

        $event->setRequest($request);
        $event->setResult(array('value' => 'foo'));

        $this->selectListener->selectModel($event);

        $model = $event->getResult();

        $this->assertInstanceOf('Zend\View\Model\ViewModel', $model);
        $this->assertEquals(array('value' => 'foo'), $model->getVariables()->getArrayCopy());
    }

    public function testDoesNotStopPropagationWhenInjectErrorModelIfViewModel()
    {
        $event   = new MvcEvent();
        $request = new HttpRequest();
        $headers = $request->getHeaders();

        $accept  = new Header\Accept();
        $accept->addMediaType('text/html', 1);
        $headers->addHeader($accept);

        $event->setRequest($request);

        $this->selectListener->injectErrorModel($event);

        $this->assertFalse($event->propagationIsStopped());
    }

    public function testInjectErrorModelIfSubclassOfViewModel()
    {
        $event   = new MvcEvent();
        $request = new HttpRequest();
        $headers = $request->getHeaders();

        $accept  = new Header\Accept();
        $accept->addMediaType('application/javascript', 1);
        $headers->addHeader($accept);

        $event->setRequest($request);

        $this->selectListener->injectErrorModel($event);

        $this->assertInstanceOf('Zend\View\Model\JsonModel', $event->getViewModel());
        $this->assertTrue($event->propagationIsStopped());
    }
}
