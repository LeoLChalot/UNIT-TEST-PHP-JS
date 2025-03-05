<?php

use App\Controller\DashboardController;
use App\Controller\SecurityController;
use App\Tests\Controller\DashboardControllerTest;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    public function testController(): void
    {
        $this->assertFileExists('src/Controller/SecurityController.php');
        $this->assertFileExists('src/Controller/DashboardController.php');
    }

    public function testSecurityController(): void
    {
        $securityController = new SecurityController();
        $this->assertInstanceOf(SecurityController::class, $securityController);
    }

    public function testDashboardController(): void
    {
        $dashboardController = new DashboardController();
        $this->assertInstanceOf(DashboardController::class, $dashboardController);
    }





}