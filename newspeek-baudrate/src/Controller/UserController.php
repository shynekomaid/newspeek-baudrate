<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    private $entityManager;

    /**
     * Retrieves a user by their ID.
     *
     * @param int $id The ID of the user to retrieve.
     *
     * @return User|null The user object if found, otherwise null.
     */
    private function getUserById($id)
    {
        if (empty($id)) {
            return null;
        } else {
            return $this->entityManager->getRepository(User::class)->find($id);
        }
    }

    /**
     * UserController constructor.
     *
     * @param EntityManagerInterface $entityManager The entity manager interface for database operations.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Return user information by ID.
     *
     * @param int $id User ID.
     *
     * @return JsonResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    #[Route('/user/{id}', name: 'app_user', methods: ['GET'])]
    public function index($id): JsonResponse
    {
        $user = $this->getUserById($id);

        if ($user === null) {
            return new JsonResponse([
                'error' => 'User ID is required and cannot be empty.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!$user) {
            return new JsonResponse([
                'error' => 'User not found.'
            ], JsonResponse::HTTP_NOT_FOUND);
        }
        $addresses = $this->entityManager->getRepository(Address::class)->findBy(['user' => $user]);

        return $this->json([
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
            'phone' => $user->getPhone(),
            'email' => $user->getEmail(),
            'language' => $user->getLanguage(),
            'theme' => $user->getTheme(),
            'deviceId' => $user->getDeviceId(),
            // In TA not required createdAt and updatedAt but by realisation has its data
            // 'createdAt' => $user->getCreatedAt(),
            // 'updatedAt' => $user->getUpdatedAt(),
            'addresses' => array_map(function ($address) {
                // get services for each address
                $services = $this->entityManager->getRepository(Service::class)->findBy(['address' => $address->getId()]);

                $serviceData = [
                    'internet' => null,
                    'tv' => null,
                    'ip' => null,
                ];

                foreach ($services as $service) {
                    $type = $service->getType();
                    if (array_key_exists($type, $serviceData)) {
                        $serviceData[$type] = $service->getValue();
                    }
                }

                $serviceData = array_filter($serviceData, fn($value) => $value !== null);

                return [
                    'address' => $address->getAddress(),
                    'status' => $address->getStatus(),
                    'tariff' => $address->getTariff(),
                    'balance' => $address->getBalance(),
                    'services' => $serviceData,
                ];
            }, $addresses)
        ]);
    }

    /**
     * Update a user.
     *
     * @param int $id The ID of the user to update.
     * @param Request $request The request object.
     * @param EntityManagerInterface $entityManager The entity manager interface for database operations.
     *
     * @return JsonResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * Body parameters:
     * - password: string, optional
     * - phone: string, optional
     * - email: string, optional
     * - language: string, optional, default: uk
     * - theme: string, optional, default: light
     * - deviceId: string, optional
     */
    #[Route('/user/{id}', name: 'app_user_update', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return new JsonResponse([
                'error' => 'User not found.'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['password'])) {
            $user->setPassword($data['password']);
        }
        if (isset($data['phone'])) {
            $user->setPhone($data['phone']);
        }
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['language'])) {
            $user->setLanguage($data['language']);
        }
        if (isset($data['theme'])) {
            $user->setTheme($data['theme']);
        }
        if (isset($data['deviceId'])) {
            $user->setDeviceId($data['deviceId']);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'User updated successfully',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'phone' => $user->getPhone(),
                'language' => $user->getLanguage(),
                'theme' => $user->getTheme(),
                'deviceId' => $user->getDeviceId(),
            ]
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Creates a new user.
     *
     * @param Request $request The request object.
     * @param EntityManagerInterface $entityManager The entity manager interface for database operations.
     *
     * @return JsonResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * Body parameters:
     * - username: string
     * - password: string
     * - phone: string
     * - email: string
     * - language: string, optional, default: uk
     * - theme: string, optional, default: light
     * - deviceId: string, optional
     */
    #[Route('/user', name: 'app_user_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset(
            $data['username'],
            $data['password'],
            $data['phone'],
            $data['email']
        )) {
            return $this->json([
                'error' => 'Missing required parameters: username, password, phone, email'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['username' => $data['username']]);
        if ($existingUser) {
            return $this->json([
                'error' => 'Username already exists'
            ], JsonResponse::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setUsername($data['username']);
        $user->setPassword($data['password']);
        $user->setPhone($data['phone']);
        $user->setEmail($data['email']);
        $user->setLanguage($data['language'] ?? 'uk');
        $user->setTheme($data['theme'] ?? 'light');
        $user->setDeviceId($data['deviceId'] ?? null);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'User created successfully',
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'phone' => $user->getPhone(),
                'language' => $user->getLanguage(),
                'theme' => $user->getTheme(),
                'deviceId' => $user->getDeviceId(),
            ]
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * Deletes a user by its ID.
     *
     * @param int $id The ID of the user to delete.
     * @param EntityManagerInterface $entityManager The entity manager interface for database operations.
     *
     * @return JsonResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    #[Route('/user/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        // TODO: allow remove user if no address exists
        if (!$user) {
            return new JsonResponse([
                'error' => 'User not found.'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'User deleted successfully.'
        ], JsonResponse::HTTP_OK);
    }
}
