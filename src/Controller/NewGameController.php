<?php

namespace App\Controller;
use App\Entity\Game;
use App\Entity\Play;
use App\Form\NewGameType;
use App\Repository\UserRepository;
use App\Repository\PlayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Security\Core\Security;
class NewGameController extends AbstractController
{
	private $userRepository;
	
	private $playRepository;

    public function __construct(UserRepository $userRepository, PlayRepository $playRepository)
    {
		$this->userRepository = $userRepository;
		$this->playRepository = $playRepository;
    }
	
    /**
     * @Route("/new/game", name="new_game")
     */
    public function index(Request $request)
    {
        $game = new Game();
        $form = $this->createForm(NewGameType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {           
			$this->createGame($game, $request);
			return $this->redirectToRoute('play', ['id'=>$game->getId()]);			
        }
        return $this->render('new_game/index.html.twig', ['form' => $form->createView(),],);
    }
	
	public function createGame(Game $game, Request $request)
	{
		$game->setFirstUserName($this->userRepository->findOneBy(['email' => $request->getSession()->get(Security::LAST_USERNAME, '')])->GetName());
		$game->setSecondUserName('');
		$game->setLastMoveUserName('');
        $em = $this->getDoctrine()->getManager();
        $em->persist($game);
        $em->flush(); 
		for($i=0; $i<$game->getSize(); $i++)
			for($j=0; $j<$game->getSize(); $j++) {
		        $play=new Play();
		        $play->SetX($i)->SetY($j)->SetIdGame($game->getId());
	            $em = $this->getDoctrine()->getManager();
                $em->persist($play);
                $em->flush(); 
			}
	}
}
