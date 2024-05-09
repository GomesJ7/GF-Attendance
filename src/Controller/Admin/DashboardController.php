<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Utilisateur;
use App\Entity\Formation;
use App\Entity\Promotion;
use App\Entity\Matiere;
use App\Entity\SalleClasse;
use App\Entity\Emarger;
use App\Entity\Session;
use Symfony\Component\Security\Http\Attribute\IsGranted;
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GF Attendance');
    }


    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-list', Utilisateur::class);
        yield MenuItem::linkToCrud('Promotion', 'fas fa-list', Promotion::class);
        yield MenuItem::linkToCrud('Formation', 'fas fa-list', Formation::class);
        yield MenuItem::linkToCrud('Matiere', 'fas fa-list', Matiere::class);
        yield MenuItem::linkToCrud('Salle de classe', 'fas fa-list', SalleClasse::class);
        yield MenuItem::linkToCrud('Session', 'fas fa-list', Session::class);
        yield MenuItem::linkToCrud('Emarger', 'fas fa-list', Emarger::class);

    }
}
