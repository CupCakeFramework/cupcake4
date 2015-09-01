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

class ServiceManagerTest extends PHPUnit_Framework_TestCase
{
    public function testCanRegisterService()
    {
        $service = new ServiceManager();
        $stdClass = new Stdclass;

        $service->addFactory('stdClass', function () use ($stdClass) {
            return $stdClass;
        });

        $this->assertSame($stdClass, $service->get('stdClass'));
    }

    public function testThrowsExceptionWhenServiceIdIsNotAStringWhenAddingAFactory()
    {
        $service = new ServiceManager();
        $this->setExpectedException('Cupcake\Service\Exception\ContainerException');
        $service->addFactory(1, function () {
        });
    }

    public function testThrowsExceptionWhenAddingACallableFactory()
    {
        $service = new ServiceManager();
        $this->setExpectedException('Cupcake\Service\Exception\ContainerException');
        $service->addFactory('ServiceFakeName', 'MyServiceFactoryIsHereDuh');
    }

    public function testThrowsExceptionWhenServiceIdIsNotAStringWhenGettingAService()
    {
        $service = new ServiceManager();
        $this->setExpectedException('Cupcake\Service\Exception\ContainerException');
        $service->get(1);
    }

    public function testThrowsExceptionWhenGettingAInexistentService()
    {
        $service = new ServiceManager();
        $this->setExpectedException('Cupcake\Service\Exception\NotFoundException');
        $service->get('NonExistantService');
    }

}
