<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Yacht Dealers API',
    description: 'REST API for catamaran charters, bookings, guests, summaries, and personal requests in Dar es Salaam and Zanzibar.'
)]
#[OA\Server(url: '/api/v1', description: 'API v1')]
#[OA\Tag(name: 'Catamarans', description: 'Yacht catalog management')]
#[OA\Tag(name: 'Photos', description: 'Catamaran photo management')]
#[OA\Tag(name: 'Routes', description: 'Catamaran route management')]
#[OA\Tag(name: 'Bookings', description: 'Charter booking management')]
#[OA\Tag(name: 'Extras', description: 'Booking additional services')]
#[OA\Tag(name: 'Personal Requests', description: 'Personal requests linked to bookings')]
#[OA\Tag(name: 'Others', description: 'Additional details for personal requests')]
#[OA\Tag(name: 'Guests', description: 'Guest contact details for bookings')]
#[OA\Tag(name: 'Summaries', description: 'Order summary linking booking, guest, and catamaran photo')]
class ApiDocumentation {}
