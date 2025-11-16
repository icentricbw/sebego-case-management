<?php

namespace App\Controller\Admin;

use App\Entity\Archive;
use App\Entity\Client;
use App\Entity\CommunicationLog;
use App\Entity\Document;
use App\Entity\FileMovement;
use App\Entity\Matter;
use App\Entity\MatterUpdate;
use App\Entity\Task;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Option 1: Render custom dashboard
        return $this->render('admin/dashboard.html.twig');

        // Option 2: Redirect to a specific CRUD controller
        // return $this->redirect($this->generateUrl('admin', ['crudAction' => 'index', 'crudControllerFqcn' => MatterCrudController::class]));
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sebego Legal CMS')
            ->setFaviconPath('favicon.ico')
            ->renderContentMaximized()
            ->renderSidebarMinimized()
            ->setLocales(['en']);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Case Management');
        yield MenuItem::linkToCrud('Matters', 'fa fa-briefcase', Matter::class);
        yield MenuItem::linkToCrud('Clients', 'fa fa-users', Client::class);
        yield MenuItem::linkToCrud('Matter Updates', 'fa fa-clipboard-list', MatterUpdate::class);

        yield MenuItem::section('Tasks & Workflow');
        yield MenuItem::linkToCrud('Tasks', 'fa fa-tasks', Task::class);
        yield MenuItem::linkToCrud('Communications', 'fa fa-comments', CommunicationLog::class);

        yield MenuItem::section('Documents & Files');
        yield MenuItem::linkToCrud('Documents', 'fa fa-file-alt', Document::class);
        yield MenuItem::linkToCrud('Archives', 'fa fa-archive', Archive::class);
        yield MenuItem::linkToCrud('File Movements', 'fa fa-exchange-alt', FileMovement::class);

        yield MenuItem::section('Administration');
        yield MenuItem::linkToCrud('Users', 'fa fa-user-shield', User::class)
            ->setPermission('ROLE_ADMIN');

        yield MenuItem::section('Reports');
        yield MenuItem::linkToRoute('Weekly Progress', 'fa fa-chart-line', 'admin_reports_weekly')
            ->setPermission('ROLE_LAWYER');
        yield MenuItem::linkToRoute('Matter Statistics', 'fa fa-chart-bar', 'admin_reports_statistics')
            ->setPermission('ROLE_LAWYER');
    }
}
