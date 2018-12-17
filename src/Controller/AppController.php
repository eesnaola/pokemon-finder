<?php

namespace App\Controller;

use App\Service\PokeApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app")
     */
    public function index(Request $request, PokeApiService $pokeApiService)
    {
        $result = array();

        $form = $this->createFormBuilder()
            ->add('name', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Ingrese el nombre a buscar',
                ),
                'label' => false,
            ))
            ->add('save', SubmitType::class, array('label' => 'Buscar'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pokemons = $pokeApiService->getPokemons();

            foreach ($pokemons as $key => $pokemon) {
                if (count($result) > 2)
                    break;
                if (stristr($pokemon->getName(), $form->getData()['name'])) {
                    $pokemon->setImage($pokeApiService->getImage($pokemon->getUrl()));
                    $result[] = $pokemon;
                }
            }
            if (empty($result))
                $this->addFlash(
                    'notice',
                    'No se han encontrado resultados para tu búsqueda.'
                );
        }

        return $this->render('app/index.html.twig', [
            'form' => $form->createView(),
            'result' => $result
        ]);
    }
}
