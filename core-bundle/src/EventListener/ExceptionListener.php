<?php

/**
 * This file is part of Contao.
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\CoreBundle\EventListener;

use Contao\CoreBundle\Exception\DieNicelyException;
use Contao\CoreBundle\Exception\ResponseExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Handle exceptions and create a proper response containing the error screen when debug mode is not active.
 *
 * @author Christian Schiffler <https://github.com/discordier>
 */
class ExceptionListener
{
    /**
     * @var string
     */
    private $environment;

    /**
     * Create a new instance.
     */
    public function __construct($environment)
    {
        $this->environment = $environment;
    }

    /**
     * Forwards the request to the Frontend class if there is a page object.
     *
     * @param GetResponseForExceptionEvent $event The event object
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // Search if an response is somewhere in the exception list.
        $response = $this->checkResponseException($event->getException());
        if ($response instanceof Response) {
            $event->setResponse($response);

            return;
        }

        // FIXME: log the exception here if we do not find a better place to log via monolog from outside.

        // FIXME: do we really want this?
        // If not in prod, exit here.
        if ($this->environment !== 'prod') {
            return;
        }

        $exception = $event->getException();
        do {
            if ($exception instanceof DieNicelyException) {
                $event->setResponse($this->handleDieNicelyException($exception));

                return;
            }
        } while (null !== ($exception = $exception->getPrevious()));

        // If nothing worked out, show the good old Contao screen.
        $event->setResponse(
            $this->handleDieNicelyException(
                new DieNicelyException(
                    'be_error',
                    'An error occurred while executing this script!',
                    500,
                    array(),
                    0,
                    $event->getException()
                )
            )
        );
    }

    /**
     * Handle the die nicely exceptions.
     *
     * @param DieNicelyException $exception The exception
     *
     * @return Response The created response.
     */
    protected function handleDieNicelyException(DieNicelyException $exception)
    {
        $template = $exception->getTemplate();
        if ($response = $this->tryReadTemplate(sprintf('%s/templates/%s.html5', TL_ROOT, $template))) {
           return $response;
        } elseif ($response = $this->tryReadTemplate(
            sprintf('%s/../../contao/templates/backend/%s.html5', __DIR__, $template)
        )) {
            return $response;
        }

        // Show the message passed in the exception on the screen when template could not be located.
        return new Response($exception->getMessage(), 500, array('Content-type' => ' text/html; charset=utf-8'));
    }

    /**
     * Try to read a template.
     *
     * @param string $template The file path
     *
     * @return null|Response
     */
    private function tryReadTemplate($template)
    {
        if (file_exists($template)) {
            ob_start();
            include $template;

            return new Response(ob_get_clean(), 500, array('Content-type' => ' text/html; charset=utf-8'));
        }

        return null;
    }

    /**
     * Check if the exception or any exception in the chain implements the response exception interface.
     *
     * @param \Exception $exception The exception to walk on.
     *
     * @return null|Response
     */
    private function checkResponseException($exception)
    {
        do {
            if ($exception instanceof ResponseExceptionInterface) {
                return $exception->getResponse();
            }
        } while (null !== ($exception = $exception->getPrevious()));

        return null;
    }
}
