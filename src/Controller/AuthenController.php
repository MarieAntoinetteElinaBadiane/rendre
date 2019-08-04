<?php

namespace App\Controller;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use  Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
/**
 * @Route("/api")
 */
class AuthenController extends AbstractController
{
    /**
     * @Route("/simpleuser", name="simpleuser", methods={"POST"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $sms='message';
        $status='status';

        $values = json_decode($request->getContent());
        if(isset($values->username,$values->password)) {

           

            $user = new User();
            $user->setNom($values->nom);
            $user->setPrenom($values->prenom);
            $user->setStatut($values->statut);
            $user->setUsername($values->username);
            $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
                if ($values->roles==1) {
                    $user->setRoles(['SUPER_ADMIN']);
                }
                if ($values->roles==2) {
                    $user->setRoles(['ADMIN']);
                }
                if ($values->roles==3) {
                    $user->setRoles(['USER']);
                }

                if ($values->roles==4) {
                    $user->setRoles(['CAISSIER']);
                }
                
                

                $entityManager = $this->getDoctrine()->getManager();
                
                $entityManager->persist($user);
                 $entityManager->flush();

            $data = [
                $status => 201,
                $sms => 'Les propriétés du user ont été bien ajouté'
            ];
            return new JsonResponse($data, 201);
        }
        $data = [
            $status => 500,
            $sms => 'Vous devez renseigner les clés username et password'
        ];
        return new JsonResponse($data, 500);
    }

     /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request)
    {
        $user = $this->getUser();
        return $this->json([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles()
        ]);
    }
        }
    
