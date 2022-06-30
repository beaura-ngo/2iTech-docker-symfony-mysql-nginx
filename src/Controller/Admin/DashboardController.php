<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    public function __construct(private ProductRepository $productRepo) {}

    #[Route('/admin', name: 'admin_dashboard')]
    public function index(CategoryRepository $categoryRepo,
                          ProductRepository $productRepo,
                          OrderRepository $orderRepo,
                          UserRepository $userRepo
                          ): Response
    {
        $categoryCount = $categoryRepo->count([]);
        $productCount = $productRepo->count([]);
        $orderCount = $orderRepo->count([]);
        $userCount = $userRepo->count([]);

        // $fiveProducts = $this->productRepo->findFiveLastAddedProducts($product);
        $fiveProducts = $productRepo->findFiveLastAddedProducts(5);
        $categories = $categoryRepo->findThreeLastCategoriess(3);
        return $this->render('admin/dashboard/index.html.twig', compact('categoryCount', 'productCount', 'orderCount', 'userCount', 'fiveProducts', 'categories') 
    );
    }
}
