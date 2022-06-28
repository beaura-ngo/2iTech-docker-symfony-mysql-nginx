<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products', name: 'app_products_')]
class ProductController extends AbstractController
{

    public function __construct(private ProductRepository $productRepo) {}

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

        // $relatedProducts = $this->productRepo->findRelatedProductsByCategory($product);
        $relatedProducts = $this->productRepo->findProductInSameCategoryDql($product);
        // affiche contenu puis sort
        // dd($product);
        return $this->render('product/details.html.twig', compact('product', 'relatedProducts'));
        // [
        //     'product' => $product->getName(),
        // ]);
    }
}
