<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function dashboardAction(Request $request)
    {
        return $this->render('admin/main/dashboard.html.twig');
    }

}
