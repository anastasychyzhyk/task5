<?php

namespace App\Controller;
use App\Form\PlayType;
use App\Entity\Game;
use App\Entity\Play;
use App\WebSocket;
use App\Repository\PlayRepository;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Security;


use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use App\Websocket\MessageHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PlayController extends AbstractController
{
	private $playRepository;
    private $gameRepository;
	private $userRepository;

    public function __construct(PlayRepository $playRepository, GameRepository $gameRepository, UserRepository $userRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->playRepository = $playRepository;
		$this->userRepository = $userRepository;	
    }
	
    /**
     * @Route("/play/{id}", name="play")
     */
    public function index(Request $request, $id)
    {
		$form = $this->createForm(PlayType::class);
        $form->handleRequest($request);
		$play = $this->playRepository->findBy(['idGame' => $id]);
		$game = $this->gameRepository->findOneBy(['id' => $id]);      	
        if ($form->isSubmitted()) {
			if(!empty($_POST['cell'])) {
				$this->userSelectCell($request, $game, $id);
			}
            $winner=$this->checkIsGameFinish($request, $game, $play);
			if($winner!=NULL)
				echo $winner;			
		}
        return $this->render('play/index.html.twig', ['form' => $form->createView(),'play' => $play, 'game'=>$game]);		
    }  
	
	public function userSelectCell(Request $request, Game $game, int $id)
	{
		$userName=$this->userRepository->findOneBy(['email' => $request->getSession()->get(Security::LAST_USERNAME, '')])->GetName();
	    $cellPosition=explode(" ", $_POST['cell'][0]);
		$cell = $this->playRepository->findOneBy(['idGame' => $id,'x'=>$cellPosition[0], 'y'=>$cellPosition[1] ]);
		if($cell->getUserName()==NULL) {
		    $this->playRepository->addUserName($cell, $userName);
            $this->gameRepository->addLastMoveUserName($userName, $game);
		}
	}
	
	public function checkIsGameFinish(Request $request, Game $game, array $play)
    {
		$gameSize=$game->getSize();
		$winner=$this->findRowColumnWinner($play, $gameSize, true);
		if($winner!=NULL) 
			return $winner;
		$winner=$this->findRowColumnWinner($play, $gameSize, false);
		if($winner!=NULL) 
			return $winner;
		$winner=$this->findMainDiagonalWinner($play, $gameSize);
		if($winner!=NULL) 
			return $winner;
		$winner=$this->findSideDiagonalWinner($play, $gameSize);
			return $winner;	
	} 
	
    public function findRowColumnWinner($play, $gameSize, $isRowMode)
    {
	    for($i=0; $i<$gameSize; $i++){
			$winner=$play[$this->getIndex($i, 0, $gameSize, $isRowMode)]->getUserName();
		    for($j=0; $j<$gameSize; $j++) {
	            if($play[$this->getIndex($i, $j, $gameSize, $isRowMode)]->getUserName()!=$winner) {
			    	$winner=NULL;
					break;
				}
			}
			if($winner!=NULL)
				return $winner;
		}
		return NULL;		
    }
	
	public function findMainDiagonalWinner($play, $gameSize)
    {
		$winner=$play[0]->getUserName();
	    for($i=$gameSize+1; $i<$gameSize*$gameSize; $i+=$gameSize+1){					   
	        if($play[$i]->getUserName()!=$winner) {
			   	return NULL;
			}		
		}
		return $winner;		
    }
	
	public function findSideDiagonalWinner($play, $gameSize)
    {
		$winner=$play[$gameSize-1]->getUserName();
	    for($i=2*$gameSize-2; $i<$gameSize*$gameSize; $i+=$gameSize-1){					   
			if($play[$i]->getUserName()!=$winner) {
			   	return NULL;
			}		
		}
		return $winner;		
    }       
	
	public function getIndex(int $i, int $j, int $gameSize, bool $isRowMode)
	{
		if($isRowMode) {
			return $j+$i*$gameSize;
		}
		else {
			return $i+$j*$gameSize;
		}
	}
}
