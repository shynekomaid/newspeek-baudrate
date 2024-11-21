<?php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class ServiceController extends AbstractController
{
    private $entityManager;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager The entity manager interface for database operations.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Retrieves a service by its ID.
     *
     * @param int $id The ID of the service to retrieve.
     *
     * @return Service|null The service object if found, otherwise null.
     */
    private function getServiceById($id)
    {
        if (empty($id)) {
            return null;
        } else {
            return $this->entityManager->getRepository(Service::class)->find($id);
        }
    }

    /**
     * Return service information by ID.
     *
     * @param int $id The ID of the service to retrieve.
     *
     * @return JsonResponse
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    #[Route('/service/{id}', name: 'app_service', methods: ['GET'])]
    public function index($id): JsonResponse
    {
        $service = $this->getServiceById($id);

        if ($service === null) {
            return new JsonResponse([
                'error' => 'Service ID is required and cannot be empty.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!$service) {
            return new JsonResponse([
                'error' => 'Service not found.'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id' => $service->getId(),
            'address_id' => $service->getAddress()->getId(),
            'type' => $service->getType(),
            'value' => $service->getValue(),
        ]);
    }

    /**
     * Creates a new service and returns the newly created service in JSON format.
     *
     * @param Request $request The HTTP request containing the service data in JSON format.
     *
     * @return JsonResponse The newly created service in JSON format.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    #[Route('/service', name: 'app_service_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['address_id'], $data['type'], $data['value'])) {
            return new JsonResponse([
                'error' => 'Missing required parameters: address_id, type, value'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $address = $this->entityManager->getRepository(Address::class)->find($data['address_id']);
        if (!$address) {
            return new JsonResponse([
                'error' => 'Address not found.'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        // check if address already has this service
        $existingService = $this->entityManager->getRepository(Service::class)->findOneBy(['address' => $address, 'type' => $data['type']]);
        if ($existingService) {
            return new JsonResponse([
                'error' => 'Service with the same type already exists for this address.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $service = new Service();
        $service->setAddress($address);
        $service->setType($data['type']);
        $service->setValue($data['value']);

        $this->entityManager->persist($service);
        $this->entityManager->flush();

        return new JsonResponse([
            'id' => $service->getId(),
            'address_id' => $service->getAddress()->getId(),
            'type' => $service->getType(),
            'value' => $service->getValue(),
            // 'created_at' => $service->getCreatedAt()->format('Y-m-d H:i:s'),
            // 'updated_at' => $service->getUpdatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Deletes a service by its ID and returns a JSON response containing a success message if the service is deleted, or an error message if the service is not found.
     *
     * @param int $id The ID of the service to delete.
     *
     * @return JsonResponse A JSON response containing a success message if the service is deleted, or an error message if the service is not found.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    #[Route('/service/{id}', name: 'app_service_delete', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        $service = $this->getServiceById($id);

        if ($service === null) {
            return new JsonResponse([
                'error' => 'Service ID is required and cannot be empty.'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!$service) {
            return new JsonResponse([
                'error' => 'Service not found.'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($service);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Service deleted successfully.'
        ]);
    }
}
