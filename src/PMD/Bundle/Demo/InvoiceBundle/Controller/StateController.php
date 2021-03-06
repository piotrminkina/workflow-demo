<?php

/*
 * This file is part of the PMDDemo package.
 *
 * (c) Piotr Minkina <projekty@piotrminkina.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PMD\Bundle\Demo\InvoiceBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use PMD\Bundle\StateMachineBundle\Controller\StateController as BaseStateController;
use PMD\Bundle\StateMachineBundle\Model\StatefulInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * This is temporary solution to persist object state changes, I think
 * in future it will be realize by listening on emited state machine events .
 * 
 * @author Piotr Minkina <projekty@piotrminkina.pl>
 * @package PMD\Bundle\Demo\InvoiceBundle\Controller
 */
class StateController
{
    /**
     * @var BaseStateController
     */
    protected $controller;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @param BaseStateController $controller
     * @param ObjectManager $manager
     */
    public function __construct(
        BaseStateController $controller,
        ObjectManager $manager
    ) {
        $this->controller = $controller;
        $this->manager = $manager;
    }

    /**
     * @param Request $request
     * @param StatefulInterface $object
     * @return mixed|Response
     */
    public function handleAction(Request $request, StatefulInterface $object)
    {
        $response = $this->controller->handleAction($request, $object);

        $this->manager->persist($object);
        $this->manager->flush();

        return $response;
    }
}
