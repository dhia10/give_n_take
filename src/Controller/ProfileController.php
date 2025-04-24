<?php



namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Transaction;

class ProfileController extends AbstractController

{#[Route('/profile', name: 'app_profile', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em->flush(); // Save changes to the database
    
                if ($request->isXmlHttpRequest()) { // Check for AJAX request
                    return $this->json([
                        'success' => true,
                        'message' => 'Profile updated successfully.',
                        'user' => [
                            'username' => $user->getUsername(),
                            'email' => $user->getEmail(),
                            'phonenumber' => $user->getPhonenumber(),
                        ]
                    ]);
                }
    
                $this->addFlash('success', 'Profile updated successfully.');
                return $this->redirectToRoute('app_profile');
            } else {
                if ($request->isXmlHttpRequest()) { // Return errors for AJAX requests
                    return $this->json([
                        'success' => false,
                        'errors' => $this->getFormErrors($form) // Create this method to gather form errors
                    ], 400);
                }
            }
        }
        $transactions = $em->getRepository(Transaction::class)
                      ->findBy(['user' => $user]);
        return $this->render('profile/index.html.twig', [
            'editForm' => $form->createView(),
            'transactions' => $transactions,
            'user' =>$user
        ]);
    }
    
    // Helper method to get form errors
    private function getFormErrors($form)
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return $errors;
    }
    #[Route('/profile/activity', name: 'app_profile_activity')]
    public function activity(): Response
    {
        $user = $this->getUser();
        $transactions = $user->getTransactionMade();

        return $this->render('profile/_activity.html.twig', [
            'transactions' => $transactions,
        ]);
    }


}
