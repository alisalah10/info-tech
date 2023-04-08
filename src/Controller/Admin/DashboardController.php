<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Catalogue;
use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->redirectToRoute('app_home');
        }

        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());
    }


    // return parent::index();
    //return $this->render('admin/index.html.twig');
    //$routeBuilder = $this->get(AdminUrlGenerator::class);

    //return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());

    // Option 1. You can make your dashboard redirect to some common page of your backend
    //
    // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
    // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

    // Option 2. You can make your dashboard redirect to different pages depending on the user
    //
    // if ('jane' === $this->getUser()->getUsername()) {
    //     return $this->redirect('...');
    // }

    // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
    // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
    //
    // return $this->render('some/path/my-dashboard.html.twig');


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Info-Tech');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('catalogue', 'fas fa-list', Catalogue::class);
        yield MenuItem::linkToCrud('produit', 'fas fa-list', Produit::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
    }
}
