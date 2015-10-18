<?php

namespace Services;

use Mickadoo\BaseDataBundle\MickadooBaseDataBundle;
use Mickadoo\BaseDataBundle\Services\BundleHelper;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\app\AppKernel;

class BundleHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testGetBundles()
    {
        $fatCatMockBundle = new MickadooBaseDataBundle();
        $reflection = new \ReflectionClass($fatCatMockBundle);
        $reflection_property = $reflection->getProperty('name');
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($fatCatMockBundle, 'FatCatMockBundle');

        $flyingPigBundle = new MickadooBaseDataBundle();
        $reflection = new \ReflectionClass($flyingPigBundle);
        $reflection_property = $reflection->getProperty('name');
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($flyingPigBundle, 'FlyingPigBundle');

        $mockBundles = [$fatCatMockBundle, $flyingPigBundle];

        /** @var AppKernel|\PHPUnit_Framework_MockObject_MockObject $mockKernel */
        $mockKernel = $this->getMockBuilder(AppKernel::class)->disableOriginalConstructor()->getMock();
        $mockKernel
            ->expects($this->any())->method('getBundles')->willReturn($mockBundles);

        $bundleHelper = new BundleHelper($mockKernel);

        $this->assertEquals([$fatCatMockBundle, $flyingPigBundle], $bundleHelper->getBundles());
        $this->assertEquals([$fatCatMockBundle], $bundleHelper->getBundles('FatCat'));
    }

    public function testGetBundleFolderFromNamespace()
    {
        /** @var AppKernel|\PHPUnit_Framework_MockObject_MockObject $mockKernel */
        $mockKernel = $this->getMockBuilder(AppKernel::class)->disableOriginalConstructor()->getMock();

        $bundleHelper = new BundleHelper($mockKernel);

        $this->assertEquals(
            'BaseDataBundle',
            $bundleHelper->getBundleFolderFromNamespace(MickadooBaseDataBundle::class)
        );
    }

    public function testGetBundleFolderFromPath()
    {
        /** @var AppKernel|\PHPUnit_Framework_MockObject_MockObject $mockKernel */
        $mockKernel = $this->getMockBuilder(AppKernel::class)->disableOriginalConstructor()->getMock();

        $bundleHelper = new BundleHelper($mockKernel);
        $path = '/path/to/project/src/YourNamespace/Bundles/YourBundle/YourNamespaceYourBundle.php';

        $this->assertEquals(
            'YourBundle',
            $bundleHelper->getBundleFolderFromPath($path)
        );
    }
}