<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserInformations;
use App\Repository\UserInformationsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #region GET ALL USERS
    #[OA\Get(
        tags: ["User"],
        summary: "Get all users",
    )]
    #[Route("/api/users", name: "user.all", methods: ["GET"])]
    public function GetAll(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        return $this->json($users);
    }
    #endregion

    
    #region DELETE ONE USER BY ID
    #[OA\Delete(
        tags: ["User"],
        summary: "Delete one user by id",
    )]
    #[Route("/api/users/{id}/delete", name: "user.delete", methods: ["DELETE"])]
    public function Delete(User $user = null, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$user) {
            $response = [
                "success" => false,
                "message" => "User not found",
            ];
            return $this->json($response, 404);
        }

        $entityManager->remove($user);

        $entityManager->flush();

        $response = [
            "success" => true,
            "message" => "User deleted successfully",
        ];

        return $this->json($response);
    }
    #endregion


    #region CREATE A NEW USER
    #[Route("/api/users/new", name: "user.new", methods: ["POST"])]
    #[OA\Post(
        tags: ["User"],
        summary: "Create a new user with provided data",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(
                            property: "name",
                            type: "string",
                            example: "Paul"
                        ),
                        new OA\Property(
                            property: "age",
                            type: "integer",
                            example: "18"
                        ),
                        new OA\Property(
                            property: "gender",
                            type: "string",
                            example: "male"
                        )
                    ]
                )
            )
        ),
    )]
    public function create(Request $request, EntityManagerInterface $manager): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $user = new User();
        $user->setName($requestData["name"])
        ->setGender($requestData["gender"])
        ->setAge($requestData["age"]);

        $manager->persist($user);

        $manager->flush();

        $response = [
            "success" => true,
            "message" => "User added successfully"
        ];

        return $this->json($response);
    }
    #endregion
}