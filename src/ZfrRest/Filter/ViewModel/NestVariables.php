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

namespace ZfrRest\Filter\ViewModel;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Zend\Filter\AbstractFilter;
use Zend\View\Model\ModelInterface;
use ZfrRest\Filter\Exception;

/**
 * ObjectNameWrapper. This filter allows to wrap the results in a view model by the name of the
 * underlying object. For instance, for the given Json representation: {name: 'Foo', type: 'Bar'}, if the object
 * has the name "Baz", it will results to : {'Baz': {name: 'Foo', type: 'Bar'}}
 *
 * @license MIT
 * @since   0.0.1
 */
class NestVariables extends AbstractFilter
{
    /**
     * @var ClassMetadata
     */
    protected $classMetadata;


    /**
     * @param ClassMetadata $classMetadata
     */
    public function __construct(ClassMetadata $classMetadata)
    {
        $this->classMetadata = $classMetadata;
    }

    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return mixed
     */
    public function filter($value)
    {
        if (!$value instanceof ModelInterface) {
            throw new Exception\RuntimeException(sprintf(
                'Only classes implementing ModelInterface can be filtered by this filter'
            ));
        }

        $variables = $value->getVariables();
        $class     = end(explode('\\', $this->classMetadata->getName()));

        $value->clearVariables();
        $value->setVariable($class, $variables);
    }
}
