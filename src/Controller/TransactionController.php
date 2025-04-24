<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use DateTime;
#[Route('/transaction')]
final class TransactionController extends AbstractController
{
    #[Route(name: 'app_transaction_index', methods: ['GET'])]
    public function index(TransactionRepository $transactionRepository): Response
    {
        return $this->render('transaction/index.html.twig', [
            'transactions' => $transactionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_transaction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, ProductRepository $productRepository): Response
    {
        $user = $this->getUser();
        $transaction = new Transaction();
    
        if ($request->isMethod('POST')) {
            $silver = (int) $request->request->get('silver', 0);
            $gold = (int) $request->request->get('gold', 0);
            
            $phone = $request->request->get('phone');
            $productId = $request->request->get('product_id');
    
            // Fetch the product
            $product = $productRepository->find($productId);
            
            // Check if the user has enough silver and gold
            if ($user->getSilverCoins() >= $silver && $user->getGoldCoins() >= $gold) {
                // Deduct the amounts from user's coins
                $user->setSilverCoins($user->getSilverCoins() - $silver);
                $user->setGoldCoins($user->getGoldCoins() - $gold);
    
                // Set product's prices to null
                $product->setSilverprice(null);
                $product->setGoldprice(null);
    
                // Create the transaction record
                $transaction->setUser($user);
                $transaction->setProduct($product);
                $transaction->setCreatedAt(new \DateTimeImmutable());
                $entityManager->persist($transaction);

                $entityManager->flush();
    
                // Update user's phone number if not set
                if (empty($user->getPhonenumber())) {
                    $user->setPhonenumber($phone);
                }
    
                $entityManager->persist($user); // Update user with new phone if required
                $entityManager->flush();
    
                return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
            } else {
                // Add an error message here (flash message) if insufficient funds
                $this->addFlash('error', 'Insufficient funds for this transaction.');
            }
        }
    
        return $this->redirectToRoute('app_product_index');
    }

    #[Route('/{id}', name: 'app_transaction_show', methods: ['GET'])]
    public function show(Transaction $transaction): Response
    {
        return $this->render('transaction/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transaction/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transaction->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($transaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_transaction_index', [], Response::HTTP_SEE_OTHER);
    }
}
