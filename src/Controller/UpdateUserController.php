<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UpdateUserController extends Controller
{
    /**
     * @Route("/update/user/{id}", name="update_user")
     */
    public function __invoke(Request $request, $id)
    {
        $em = $this->getDoctrine()->getRepository(User::class);

        $user = $em->find($id);

        $form = $this->get('form.factory')->create(UserType::class , $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $request->files->get('user')['images'];
            foreach ($files as $key => $file) {
                $filename = $this->generateUniqueFilename().'.'.$file['file']->guessExtension();

                $file['file']->move($this->getParameter('img_directory'), $filename);
                $image = new Image();
                $image->setPath($filename);
                $image->setUser($user);
                $user->addImage($image);
            }
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect('/');
        }

        return $this->render('update_user/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}
