<?php

namespace App\Http\Controllers;

use App\Core\Application\Commands\CreateReservationCommand;
use App\Core\Application\Queries\ReservationsQuery;
use App\Core\Infrastructure\Models\Reservation;
use App\Core\Infrastructure\Repositories\ReservationRepository;
use App\Http\Resources\ReservationResource;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationRepository $reservationRepository,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/reservations",
     *     summary="Get list of reservations",
     *     tags={"reservations"},
     *     @OA\Response(
     *         response=200,
     *         description="List of reservations"
     *     )
     * )
     */
    public function list(ReservationsQuery $reservationQuery)
    {
        return ReservationResource::collection($reservationQuery->listAll());
    }

    /**
     * @OA\Get(
     *     path="/api/reservations/{id}",
     *     summary="Get reservation by id",
     *     tags={"reservations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Reservation id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation"
     *     )
     * )
     */
    public function get(int $id, ReservationsQuery $reservationQuery)
    {
        $reservation = $reservationQuery->getOne($id);
        return new ReservationResource($reservation);
    }

    /**
     * @OA\Post(
     *     path="/api/reservations",
     *     summary="Create reservation",
     *     tags={"reservations"},
     *     @OA\RequestBody(
     *         description="Reservation object",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Reservation")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reservation"
     *     )
     * )
     */
    public function create(CreateReservationCommand $createReservation)
    {
        return new ReservationResource($createReservation->execute());
    }
}
