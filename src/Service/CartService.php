<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService{

    public $session;
    protected $productRepo;

    public function __construct(SessionInterface $session, ProductRepository $productRepo) {
        $this->session = $session;
        $this->productRepo = $productRepo;
    }

    public function addProduct(int $id){
        $panier = $this->session->get('panier', []);

        if (empty($panier[$id])) {
            $panier[$id] = 0;
        }

        $panier[$id]++;


        $this->session->set('panier', $panier);
    }

    // 1. sur la page /product/{slug} -> btn Add to cart   ajoute le produit(avec les infos) dans la session
    // session interface? init? start? 
    // 2. rediriger vers la page produit , apres enregistrement
    // (3. Aller sur la home)
    // 4. sur la page cart: recuperer les données de la session 
}