<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products', name: 'app_products')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/{slug}', name: 'details')]
    public function details(Product $product): Response
    {
        // affiche contenu puis sort
        // dd($product);
        return $this->render('product/details.html.twig', compact('product'));
        // [
        //     'product' => $product->getName(),
        // ]);
    }
}
