<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;

class ToDoController extends AbstractController
{
    #[Route('/todoList', name: 'todo')]
    public function index(SessionInterface $session): Response
    {
        if(!$session->has('todoList')){
            $todoDefault =[
                'dimanche'=>'football',
                'lundi'=>'revision',
                'mardi'=>'video games',
                'mercredi'=>'revision',
                'jeudi'=>'basketball',
                'vendredi'=>'lecture',
                'samedi'=>'programmation'
            ];
            $session->set('todoList',$todoDefault);
            $this->addFlash('info',"Welcome to your TODO list");

        }
        return $this->render('index.html.twig');
    }

    #[Route('/addTodo/{cle}/{valeur}', name: 'addToDo')]
    public function addToDo($cle,$valeur,SessionInterface $session){
        if(!$session->has('todoList')){
            $this->addFlash('error',"La liste n est pas encore initialisée");
        }else{
            $todoCopy=$session->get('todoList');
            if(isset($todoCopy[$cle])){
                $this->addFlash('error',"ToDo $cle deja existe");
            }else{
                $todoCopy[$cle]=$valeur;
                $session->set('todoList',$todoCopy);
                $this->addFlash('success',"ToDo $cle ajoute avec succes");

            }
        }
        return $this->redirectToRoute('todo');

    }
    #[Route('/removeTodo/{cle}/{valeur}', name: 'removeToDo')]
    public function removeToDo($cle,$valeur,SessionInterface $session){
        if(!$session->has('todoList')){
            $this->addFlash('error',"La liste n'est pas encore initialisée");
        }else{
            $todoCopy=$session->get('todoList');
            if(isset($todoCopy[$cle])){
                unset($todoCopy[$cle]);
                $this->addFlash('success',"ToDo $cle supprime avec succes");
                $session->set('todoList',$todoCopy);

            }else{
                $this->addFlash('error',"ToDo $cle n'existe que vous voulez supprimer  n existe pas");

            }
        }
        return $this->redirectToRoute('todo');

    }
    #[Route('/resetTodo', name: 'resetToDo')]
    public function resetToDo(SessionInterface $session){
        $todoDefault =[
            'dimanche'=>'football',
            'lundi'=>'revision',
            'mardi'=>'video games',
            'mercredi'=>'revision',
            'jeudi'=>'basketball',
            'vendredi'=>'lecture',
            'samedi'=>'programmation'
        ];
        $this->addFlash('success',"ToDo Reset effectué avec succés");
        $session->set('todoList',$todoDefault);
        return $this->redirectToRoute('todo');

    }

}