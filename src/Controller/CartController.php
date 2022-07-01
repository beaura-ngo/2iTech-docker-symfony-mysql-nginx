<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductsFormType;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/cart', name: 'app_cart_')]
class CartController extends AbstractController
{
    // public function __construct(ProductRepository $productRepo) {}

    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ProductRepository $productRepo): Response
    {
        $total = 0;

        $panier = $session->get('panier', []);
        $panierData = [];

        foreach ($panier as $id => $quantity) {
            $panierData[] = [
                'product' => $productRepo->find($id),
                'quantity' => $quantity
            ];
        }

        foreach ($panierData as $couple) {
            $total += $couple['product']->getPrice() * $couple['quantity'];
        }
        
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'dataElement' => $panierData,
            'total' => $total
        ]);
    }
    
    #[Route('/add/{id}', name:'add')]
    public function addToCart($id,
                              SessionInterface $session,
                            //   CartService $cartService,
                              ManagerRegistry $doctrine
                              )
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);
        $slug = $product->getSlug();

        $panier = $session->get('panier', []);

        if (empty($panier[$id])) {
            $panier[$id] = 0;
        }

        $panier[$id]++;


        $session->set('panier', $panier);
        
        // $cartService->addProduct($id);

        return $this->redirectToRoute('app_products_details', ['slug' => $slug] );
    }
}
