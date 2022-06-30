<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/order', name: 'admin_order_')]
class OrderController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(OrderRepository $orderRepo): Response
    {
        $orders = $orderRepo->findAll();
        return $this->render('admin/order/index.html.twig',
        compact('orders')
        // [
        //     'controller_name' => 'OrderController',
        // ]
    );
    }
}
