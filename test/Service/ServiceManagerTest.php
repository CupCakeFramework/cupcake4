<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Fiorani
 * Date: 28/08/2015
 * Time: 17:18
 */

namespace CupCake\Test\Service;


use Cupcake\Service\ServiceManager;
use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * Class ServiceManagerTest
 * @package CupCake\Test\Service
 */
class ServiceManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @throws \Cupcake\Service\Exception\ContainerException
     * @throws \Cupcake\Service\Exception\NotFoundException
     */
    public function testCanRegisterService()
    {
        $service = new ServiceManager();
        $stdClass = new Stdclass;

        $service->addFactory('stdClass', function () use ($stdClass) {
            return $stdClass;
        });

        $this->assertSame($stdClass, $service->get('stdClass'));
    }

    /**
     * @throws \Cupcake\Service\Exception\ContainerException
     */
    public function testThrowsExceptionWhenServiceIdIsNotAStringWhenAddingAFactory()
    {
        $service = new ServiceManager();
        $this->setExpectedException('Cupcake\Service\Exception\ContainerException');
        $service->addFactory(1, function () {
        });
    }

    /**
     * @throws \Cupcake\Service\Exception\ContainerException
     */
    public function testThrowsExceptionWhenAddingACallableFactory()
    {
        $service = new ServiceManager();
        $this->setExpectedException('Cupcake\Service\Exception\ContainerException');
        $service->addFactory('ServiceFakeName', 'MyServiceFactoryIsHereDuh');
    }

    /**
     * @throws \Cupcake\Service\Exception\ContainerException
     * @throws \Cupcake\Service\Exception\NotFoundException
     */
    public function testThrowsExceptionWhenServiceIdIsNotAStringWhenGettingAService()
    {
        $service = new ServiceManager();
        $this->setExpectedException('Cupcake\Service\Exception\ContainerException');
        $service->get(1);
    }

    /**
     * @throws \Cupcake\Service\Exception\ContainerException
     * @throws \Cupcake\Service\Exception\NotFoundException
     */
    public function testThrowsExceptionWhenGettingANonExistentService()
    {
        $service = new ServiceManager();
        $this->setExpectedException('Cupcake\Service\Exception\NotFoundException');
        $service->get('NonExistentService');
    }

}
