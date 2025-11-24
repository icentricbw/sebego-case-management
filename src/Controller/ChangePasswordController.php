<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForcePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ChangePasswordController extends AbstractController
{
    #[Route('/change-password', name: 'app_change_password')]
    public function changePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        // Check if user must change password
        $isFirstLogin = $user->isMustChangePassword();

        $form = $this->createForm(ForcePasswordFormType::class, null, [
            'is_first_login' => $isFirstLogin,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Verify current password (unless first login)
            if (!$isFirstLogin) {
                $currentPassword = $form->get('currentPassword')->getData();
                if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                    $this->addFlash('error', 'Current password is incorrect.');
                    return $this->redirectToRoute('app_change_password');
                }
            }

            // Hash and set new password
            $newPassword = $form->get('plainPassword')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);

            $user->setPassword($hashedPassword);
            $user->setMustChangePassword(false);
            $user->setLastPasswordChange(new \DateTimeImmutable());
            $user->setTempPassword(null); // Clear temp password if any

            $entityManager->flush();

            $this->addFlash('success', 'Your password has been changed successfully!');

            // Redirect to dashboard
            return $this->redirectToRoute('admin');
        }

        return $this->render('security/change_password.html.twig', [
            'changePasswordForm' => $form->createView(),
            'isFirstLogin' => $isFirstLogin,
        ]);
    }
}
