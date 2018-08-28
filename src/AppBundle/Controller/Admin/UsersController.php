<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends Controller
{
    /**
     * @Route("/admin/utilisateurs/{page}", name="admin_users", requirements={"page": "\d+"}, defaults={"page": 1})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function listUsersAction(Request $request, $page)
    {
        if($page<1){$page = 1;}

        $userRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');
        $usersList = $userRepository->getUsersByPage($page);
        $nbPageTotal = ceil(count($usersList)/UserRepository::NUM_BY_LIST_ADMIN);

        if($page>$nbPageTotal && $page != 1){$page = $nbPageTotal;}

        return $this->render('admin/users/index.html.twig', array(
            'usersList' => $usersList,
            'nbPageTotal' => $nbPageTotal,
            'page' => $page
        ));
    }

    /**
     * @Route("/admin/utilisateurs/basculer-etat/{id}", name="admin_users_toggle_state", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function toggleStateAction(User $user)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        // Les ADMIN ne sont modifiables que par les SUPER_ADMIN, le SUPER_ADMIN n'est pas modifiable
        if(($user->hasRole('ROLE_ADMIN') && !$currentUser->hasRole('ROLE_SUPER_ADMIN')) ||
            $user->hasRole(('ROLE_SUPER_ADMIN'))){
            $this->addFlash('error', 'Vous ne pouvez pas changer l\'état d\'activation de l\'utilisateur "'.$user->getUsername().'".');
            return $this->redirectToRoute('admin_users');
        }

        $user->setEnabled(!$user->isEnabled());
        $message = $user->isEnabled() ? "activé." : "désactivé.";

        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', 'L\'utilisateur "'.$user->getUsername().'" est maintenant '.$message);

        return $this->redirectToRoute('admin_users');
    }

    // NUMEROTATION ROLES
    // ======================
    // ROLE_ADMIN = 0
    // ROLE_MODERATOR = 1
    // ROLE_USER = 2
    /**
     * @Route("/admin/utilisateurs/definir-role/{id}/{role}", name="admin_users_role", requirements={"id": "\d+", "role": "[0-2]"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function setRoleAction(User $user, $role)
    {
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        // Les ADMIN ne sont modifiables que par les SUPER_ADMIN, le SUPER_ADMIN n'a pas un rôle modifiable
        if(($user->hasRole('ROLE_ADMIN') && !$currentUser->hasRole('ROLE_SUPER_ADMIN')) ||
            $user->hasRole(('ROLE_SUPER_ADMIN'))){
            $this->addFlash('error', 'Vous ne pouvez pas modifier le rôle de l\'utilisateur "'.$user->getUsername().'".');
            return $this->redirectToRoute('admin_users');
        }

        $em = $this->getDoctrine()->getManager();
        switch ($role){
            case 0:
                if(!$currentUser->hasRole('ROLE_SUPER_ADMIN')){
                    $this->addFlash('error', 'Vous ne pouvez pas accorder le rôle administrateur');
                    return $this->redirectToRoute('admin_users');
                }
                $user->setRoles(array('ROLE_ADMIN'));
                break;
            case 1:
                $user->setRoles(array('ROLE_MODERATOR'));
                break;
            case 2:
                $user->setRoles(array('ROLE_USER'));
                break;
        }
        $em->persist($user);
        $em->flush();
        $this->addFlash('success', 'Le nouveau rôle de l\'utilisateur "'.$user->getUsername().'" a bien été enregistré.');

        return $this->redirectToRoute('admin_users');
    }

}
