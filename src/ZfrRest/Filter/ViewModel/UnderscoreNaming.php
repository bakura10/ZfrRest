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

use Zend\Filter\Word\CamelCaseToUnderscore;
use Zend\View\Model\ModelInterface;
use ZfrRest\Filter\Exception;

/**
 * UnderscoreNaming. This filter converts all the keys to underscore_separated keys
 *
 * @license MIT
 * @since   0.0.1
 */
class UnderscoreNaming extends CamelCaseToUnderscore
{
    /**
     * {@inheritDoc}
     */
    public function filter($value)
    {
        if (!$value instanceof ModelInterface) {
            throw new Exception\RuntimeException(sprintf(
                'Only classes implementing ModelInterface can be filtered by this filter'
            ));
        }

        $variables = $value->getVariables();
        foreach ($variables as &$key => $value) {
            $key = parent::filter($key);
        }
    }
}
