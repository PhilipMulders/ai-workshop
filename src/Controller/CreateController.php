<?php

declare(strict_types=1);

namespace App\Controller;

use App\Type\CreateType;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Form\FormFactoryInterface;
use App\Service\World\WorldGenerator;
use Twig\Environment;

final class CreateController
{
    public function __construct(
        private Environment $twig,
        private FormFactoryInterface $factory,
        private WorldGenerator $worldGenerator,
    ) {}

    public function __invoke(Request $request): Response
    {
        $form = $this->factory->create(CreateType::class);
        $form->handleRequest();

        if ($form->isSubmitted()){
            // echo "<pre>";
            // var_dump($form->getData());
            // echo "</pre>";
            $description = $form->getData()['description'];

            $world = $this->worldGenerator->generate($description);
        }

        return new Response(body: $this->twig->render('World/create.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}