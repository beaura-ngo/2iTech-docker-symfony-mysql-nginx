<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductsFormType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/product', name: 'admin_product_')]
class ProductController extends AbstractController
{
    public function __construct(private SluggerInterface $slugger){}
    
    #[Route('/', name: 'index')]
    public function index(ProductRepository $productRepo): Response
    {
        $products = $productRepo->findAll();
        return $this->render('admin/product/index.html.twig', compact('products'));
    }

    #[Route('/add', name: 'add')]
    public function addProduct(ProductRepository $productRepo, Request $request, ManagerRegistry $doctrine): Response
    {
        $product = new Product;
        $form = $this->createForm(ProductsFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug($this->slugger->slug($product->getName())->lower());

            $do = $doctrine->getManager();
            $do->persist($form->getData());
            $do->flush();
            $this->addFlash('success', 'Product has been added!');
            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render(
            'admin/product/crud/addProduct.html.twig',
            ['form' => $form->createView()]
        );
    }
}
