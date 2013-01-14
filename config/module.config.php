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

return array(
    'zfr_rest' => array(
        /**
         * This listener automatically select the right view model from the request's Accept-Header
         */
        'register_select_model_listener' => true,

        /**
         * Options for the SelectModelListener
         */
        'select_model_listener_options' => array(
            /**
             * Associative array that map one media type (eg. text/html) to a ModelInterface FQCN
             */
            'types_to_model' => array(
                'text/html'              => 'Zend\View\Model\ViewModel',
                'application/xhtml+xml'  => 'Zend\View\Model\ViewModel',
                'application/javascript' => 'Zend\View\Model\JsonModel',
                'application/json'       => 'Zend\View\Model\JsonModel',
                'application/x-json'     => 'Zend\View\Model\JsonModel'
            ),

            /**
             * If no ModelInterface can be found, select this one (can be set to null)
             */
            'fallback_model' => 'Zend\View\Model\JsonModel'
        )
    ),

    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy'
        )
    ),
);
