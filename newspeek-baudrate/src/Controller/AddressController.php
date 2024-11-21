<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Service;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;


class AddressController extends AbstractController
{
    private $entityManager;

    /**
     * Retrieves an address by its ID.
     *
     * @param int $id The ID of the address to retrieve.
     *
     * @return Address|null The address object if found, otherwise null.
     */
    private function getAddressById($id)
    {
        if (empty($id)) {
            return null;
        } else {
            return $this->entityManager->getRepository(Address::class)->find($id);
        }
    }

    /**
     * AddressController constructor.
     *
     * @param EntityManagerInterface $entityManager The entity manager interface for database operations.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/address', name: 'app_address_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset(
            $data['user_id']
        )) {
            return new JsonResponse([
                'error' => 'No user ID provided.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $entityManager->getRepository(User::class)->find($data['user_id']);

        if (!$user) {
            return new JsonResponse([
                'error' => 'User not found.'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        if (!isset(
            $data['address']
        )) {
            return new JsonResponse([
                'error' => 'No address provided.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['status'])) {
            return new JsonResponse([
                'error' => 'No status provided.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['tariff'])) {
            return new JsonResponse([
                'error' => 'No tariff provided.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        // TODO: Add methods to adjust balance plus or minus
        if (!isset($data['balance'])) {
            return new JsonResponse([
                'error' => 'No balance provided.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $address = new Address();
        $address->setUser($user);
        $address->setAddress($data['address']);
        $address->setStatus($data['status']);
        $address->setTariff($data['tariff']);
        $address->setBalance($data['balance']);

        $entityManager->persist($address);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $address->getId(),
            'user_id' => $address->getUser()->getId(),
            'address' => $address->getAddress(),
            'status' => $address->getStatus(),
            'tariff' => $address->getTariff(),
            'balance' => $address->getBalance(),
            // 'created_at' => $address->getCreatedAt()->format('Y-m-d H:i:s'),
            // 'updated_at' => $address->getUpdatedAt()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Retrieves an address by its ID.
     *
     * @param int $id The ID of the address to retrieve.
     *
     * @return JsonResponse A JSON response containing the address information, or an error message if not found.
     */
    #[Route('/address/{id}', name: 'app_address_get', methods: ['GET'])]
    public function get($id): JsonResponse
    {
        $address = $this->getAddressById($id);

        if (!$address) {
            return new JsonResponse([
                'error' => 'Address not found.'
            ], JsonResponse::HTTP_NOT_FOUND);
        }


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

        return new JsonResponse([
            'user_id' => $address->getUser()->getId(),
            'address' => $address->getAddress(),
            'status' => $address->getStatus(),
            'tariff' => $address->getTariff(),
            'balance' => $address->getBalance(),
            'services' => $serviceData
        ]);
    }

    /**
     * Deletes an address by its ID.
     *
     * @param int $id The ID of the address to delete.
     *
     * @return JsonResponse A JSON response containing an error message if the address is not found, or a success message if the address is deleted.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    #[Route('/address/{id}', name: 'app_address_delete', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        $address = $this->getAddressById($id);

        if (!$address) {
            return new JsonResponse([
                'error' => 'Address not found.'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $entityManager = $this->entityManager;
        $entityManager->remove($address);
        $entityManager->flush();

        return new JsonResponse([
            'message' => 'Address deleted successfully.'
        ]);
    }

    /**
     * Updates an address by its ID.
     *
     * @param Request $request The request object containing the updated address data in JSON format.
     * @param int $id The ID of the address to update.
     *
     * @return JsonResponse A JSON response containing the updated address information, or an error message if the address is not found or if any required data is missing.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * Body parameters:
     * - address: string, required
     * - status: string, required
     * - tariff: string, required
     * - balance: float, required
     */
    #[Route('/address/{id}', name: 'app_address_update', methods: ['PUT'])]
    public function update(Request $request, $id): JsonResponse
    {
        $address = $this->getAddressById($id);

        if (!$address) {
            return new JsonResponse([
                'error' => 'Address not found.'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['address'])) {
            return new JsonResponse([
                'error' => 'No address provided.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['status'])) {
            return new JsonResponse([
                'error' => 'No status provided.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['tariff'])) {
            return new JsonResponse([
                'error' => 'No tariff provided.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        // TODO: Add methods to adjust balance plus or minus
        if (!isset($data['balance'])) {
            return new JsonResponse([
                'error' => 'No balance provided.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $address->setAddress($data['address']);
        $address->setStatus($data['status']);
        $address->setTariff($data['tariff']);
        $address->setBalance($data['balance']);

        $entityManager = $this->entityManager;
        $entityManager->persist($address);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $address->getId(),
            'user_id' => $address->getUser()->getId(),
            'address' => $address->getAddress(),
            'status' => $address->getStatus(),
            'tariff' => $address->getTariff(),
            'balance' => $address->getBalance(),
        ]);
    }
}
