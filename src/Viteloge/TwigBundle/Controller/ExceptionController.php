<?php

namespace Viteloge\TwigBundle\Controller {

    use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Symfony\Component\HttpKernel\Exception\FlattenException;
    use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;

    class ExceptionController {

        /**
         * @var Symfony\Component\DependencyInjection\ContainerInterface
         */
        protected $container;

        /**
         * @var bool Show error (false) or exception (true) pages by default.
         */
        protected $debug;

        /**
         *
         */
        public function __construct(ContainerInterface $container, $debug) {
            $this->container = $container;
            $this->debug = $debug;

        }

        /**
         * Returns a rendered view.
         *
         * @param string $view       The view name
         * @param array  $parameters An array of parameters to pass to the view
         *
         * @return string The rendered view
         */
        public function renderView($view, array $parameters = array()) {
            return $this->container->get('templating')->render($view, $parameters);
        }

        /**
         * Renders a view.
         *
         * @param string   $view       The view name
         * @param array    $parameters An array of parameters to pass to the view
         * @param Response $response   A response instance
         *
         * @return Response A Response instance
         */
        public function render($view, array $parameters = array(), Response $response = null) {
            return $this->container->get('templating')->renderResponse($view, $parameters, $response);
        }

        /**
         * Converts an Exception to a Response.
         *
         * A "showException" request parameter can be used to force display of an error page (when set to false) or
         * the exception page (when true). If it is not present, the "debug" value passed into the constructor will
         * be used.
         *
         * @param Request              $request   The request
         * @param FlattenException     $exception A FlattenException instance
         * @param DebugLoggerInterface $logger    A DebugLoggerInterface instance
         *
         * @return Response
         *
         * @throws \InvalidArgumentException When the exception template does not exist
         */
        public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null) {
            $currentContent = $this->getAndCleanOutputBuffering($request->headers->get('X-Php-Ob-Level', -1));
            $showException = $request->attributes->get('showException', $this->debug); // As opposed to an additional parameter, this maintains BC

            $code = $exception->getStatusCode();
            $template = $this->findTemplate($request, $request->getRequestFormat(), $code, $showException);

            return $this->render(
                $template,
                array(
                    'status_code' => $code,
                    'status_text' => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                    'exception' => $exception,
                    'logger' => $logger,
                    'currentContent' => $currentContent
                )
            );
        }

        /**
         * @param int $startObLevel
         *
         * @return string
         */
        protected function getAndCleanOutputBuffering($startObLevel) {
            if (ob_get_level() <= $startObLevel) {
                return '';
            }

            Response::closeOutputBuffers($startObLevel + 1, true);

            return ob_get_clean();
        }

        /**
         * @param Request $request
         * @param string  $format
         * @param int     $code          An HTTP response status code
         * @param bool    $showException
         *
         * @return TemplateReferenceInterface
         */
        protected function findTemplate(Request $request, $format, $code, $showException) {
            $name = $showException ? 'exception' : 'error';
            if ($showException && 'html' == $format) {
                $name = 'exception_full';
            }

            // For error pages, try to find a template for the specific HTTP status code and format
            if (!$showException) {
                $template = new TemplateReference('TwigBundle', 'Exception', $name.$code, $format, 'twig');
                if ($this->templateExists($template)) {
                    return $template;
                }
            }

            // try to find a template for the given format
            $template = new TemplateReference('TwigBundle', 'Exception', $name, $format, 'twig');
            if ($this->templateExists($template)) {
                return $template;
            }

            // default to a generic HTML exception
            $request->setRequestFormat('html');

            return new TemplateReference('TwigBundle', 'Exception', $showException ? 'exception_full' : $name, 'html', 'twig');
        }

        protected function templateExists($template) {
            return $this->container->get('templating')->exists($template);
        }

        /**
         * Gets a service by id.
         *
         * @param string $id The service id
         *
         * @return object The service
         */
        public function get($id) {
            return $this->container->get($id);
        }

    }

}
