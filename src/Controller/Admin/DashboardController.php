<?php

namespace App\Controller\Admin;

use App\Entity\Archive;
use App\Entity\CaseType;
use App\Entity\Client;
use App\Entity\CommunicationLog;
use App\Entity\Document;
use App\Entity\FileMovement;
use App\Entity\Matter;
use App\Entity\MatterUpdate;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $now = new \DateTimeImmutable();
        $weekStart = $now->modify('monday this week')->setTime(0, 0);
        $weekEnd = $now->modify('sunday this week')->setTime(23, 59, 59);

        return $this->render('admin/dashboard.html.twig', [
            // Statistics
            'activeMatters' => $this->entityManager->getRepository(Matter::class)->getActiveMattersCount(),
            'totalClients' => $this->entityManager->getRepository(Client::class)->getTotalClientsCount(),
            'pendingTasks' => $this->entityManager->getRepository(Task::class)->getPendingTasksCount(),
            'overdueTasks' => $this->entityManager->getRepository(Task::class)->getOverdueTasksCount(),
            'mattersThisWeek' => $this->entityManager->getRepository(Matter::class)->getMattersThisWeekCount($weekStart, $weekEnd),
            'archivedMatters' => $this->entityManager->getRepository(Archive::class)->getArchivedMattersCount(),

            // Lists
            'recentMatters' => $this->entityManager->getRepository(Matter::class)->getRecentMatters(5),
            'upcomingTasks' => $this->entityManager->getRepository(Task::class)->getUpcomingTasks(10),
            'mattersThisWeekList' => $this->entityManager->getRepository(Matter::class)->getMattersThisWeek($weekStart, $weekEnd, 10),
            'mattersByCaseType' => $this->entityManager->getRepository(Matter::class)->getMattersByCaseType(),

            // Date info for display
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
        ]);
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addAssetMapperEntry( 'admin');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sebego Legal CMS')
            ->setFaviconPath('favicon.ico')
            ->renderContentMaximized()
            ->setLocales(['en']);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Case Management');
        yield MenuItem::section('Clients');
        yield MenuItem::subMenu('Clients', 'fas fa-users')
            ->setSubItems([
                MenuItem::linkToCrud('Individual Clients', 'fas fa-user', Client::class)
                    ->setController(ClientIndividualCrudController::class),
                MenuItem::linkToCrud('Organization Clients', 'fas fa-building', Client::class)
                    ->setController(ClientOrganizationCrudController::class),
            ]);
        yield MenuItem::linkToCrud('Case Types', 'fas fa-folder', CaseType::class);
        yield MenuItem::linkToCrud('Matters', 'fa fa-briefcase', Matter::class);
        //yield MenuItem::linkToCrud('Clients', 'fa fa-users', Client::class);
//        yield MenuItem::linkToCrud('Matter Updates', 'fa fa-clipboard-list', MatterUpdate::class);

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
