<?php

namespace App\Cart;

use App\Cart\CartItem;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService 
{
    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function add(int $id) {
        // 1. Retrouver le panier dans la session (sous forme de tableau)
        // 2. Si il n'existe pas encore, alors prendre un tableau vide
        $cart = $this->session->get('cart', []);
        // 3. Voir si le produit ($id) existe déjà dans le tableau
        // 4. Si c'est le cas, simplement augmenter la quantité
        // 5. Sinon, ajouter le produit avec la quantité
        if (array_key_exists($id,$cart)) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        // 6. Enregistrer le tableau mis à jour dans les sessions
        $this->session->set('cart', $cart);
    }

    public function getTotal() : int {
        $total = 0;

        foreach ($this->session->get('cart', []) as $id => $qty) {
            $product = $this->productRepository->find($id);

            // si pas de produit, on "continue" la boucle en allant au prochain foreach, donc on ignore ce qu'il y a en dessous
            if(!$product) {
                continue;
            }

            $total += $product->getPrice() * $qty;

        }

        return $total;
    }

    public function getDetailedCartItems() : array {
        $detailedCart = [];

        foreach ($this->session->get('cart', []) as $id => $qty) {
            $product = $this->productRepository->find($id);

            if(!$product) {
                continue;
            }

            // $detailedCart[] = [
            //     'product' => $product,
            //     'qty' => $qty
            // ];

            $detailedCart[] = new CartItem($product, $qty);

        }

        return $detailedCart;
    }
}