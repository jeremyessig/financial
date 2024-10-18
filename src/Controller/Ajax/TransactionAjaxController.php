<?php

namespace App\Controller\Ajax;

use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ajax')]
class TransactionAjaxController extends AbstractController
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository
    ) {}

    // NON UTILISÉ !
    #[Route('/transaction', name: 'app_transaction_ajax')]
    public function index(#[MapQueryParameter('max')] int $max = 25, #[MapQueryParameter('page')] int $page = 1): Response
    {
        $page = 1;
        $pagination = $this->transactionRepository->findAllPaginated($page, $max);

        return $this->render('transaction/_wrapper.html.twig', []);
    }
}
