<?php

namespace App\Controller;
use App\Form\GameSelectorType;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;


class GameSelectorController extends AbstractController
{
	private $gameRepository;
	private $userRepository;

    public function __construct(GameRepository $gameRepository, UserRepository $userRepository)
    {
        $this->gameRepository = $gameRepository;
		$this->userRepository = $userRepository;
    }
	
    /**
     * @Route("/game/selector", name="game_selector")
     */
    public function index(Request $request)
    {			
        $form = $this->createForm(GameSelectorType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
			if(isset($_POST['new'])) return $this->redirectToRoute('new_game');
			else {
				$user=$this->userRepository->findOneBy(['email' => $request->getSession()->get(Security::LAST_USERNAME, '')]);
				$game=$this->gameRepository->findOneBy(['id' => $_POST['button'][0]]);
				if(($game->getFirstUserName()!=$user->GetName()) && ($game->getSecondUserName()== NULL))
                   $this->gameRepository->addSecondUser($user->GetName(), $this->gameRepository->findOneBy(['id' => $_POST['button'][0]]));
				return $this->redirectToRoute('play', ['id'=>$_POST['button'][0]]);			
			}
		}
		else {
			return $this->render('game_selector/index.html.twig', ['form' => $form->createView(),'games' => $this->gameRepository->findAll()]);
		}
    
    }
}
