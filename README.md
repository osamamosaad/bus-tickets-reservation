# Solution

## My hypothesis:
- I assume that passengers can book multiple seats for a single reservation order.
- I introduced a locking mechanism to prevent other passengers from reserving seats on the same bus. This ensures that only one passenger can reserve seats on a bus at a time.
- If the reservation process encounters any errors or the reservation status is anything other than "approved," the reservation will be locked for a duration of 2 minutes. This allows the passenger to have a chance to fulfill their reservation.
- Regarding reservation updates, it is preferable not to modify an existing reservation directly. Instead, a better approach is to cancel the existing reservation and create a new one with the updated details. This can be achieved by softly deleting the old reservation and creating a fresh reservation entry. This approach is particularly important when there has been a payment involved. In such cases, a refund process should be initiated before creating the new reservation with the updated information. This ensures proper management of financial transactions and maintains a clear and accurate reservation history.

## DB Desing:
I have designed the `reservation` table, which is a core table serving as the order table. This table has a relationship with the `schedule` table, which stores information about scheduled trips. We can create schedules for each bus, selecting any route, suitable date and time, and specifying a price for each seat.
Regarding the `passenger` table, its purpose is to list all the passengers' emails with a unique key, allowing us to gather statistics on their bookings.
Additionally, the `reservation_seat` table acts as a pivot table between the `reservation` and `passenger` tables, enabling the booking of multiple seats for a single reservation.

In my solution, I haven't included the ability for passengers to set their pick-up points. However, we can achieve that by introducing a new table called `station`, establishing a relationship with the `reservation` table in two column `pickup_point` & `destination`. We would need to create a path of stations for each  trip, and passengers could then choose one of them. I have omitted this feature for now.


## Application Architecture:
- I built this app following DDD especially Transaction Script pattern with hexagonal architecture.
- I separated the logic of the application from the framework, you will find it under directory named `app/core`, the directory structure as the follow:
```
app/Core
    |- Application
    -----
        | - Commands
        | - Queries

    |- Infrastructure
    -----
        |- Adapters
        |- Exceptions
        |- Models
        |- Providers
        |- Repositories

    |- Libraries
    -----
        |- Bus
        |- Common
        |- Passenger
        |- Reservation
        |- Services

    |- tests
    -----
        |- Libraries

```

**Application:** Is the application layer which contains of:
- `Commands`; here located all command that our application can do like (Create, update, etc...)
    like create Reservation here, and for future: may we will have a creation of Bus, Schdule trips, add  discount etc...).
- `Queries`;
    which located the Queries that can appplication do like(listing reservations, schdule trips, etc..)

that will be initial step of using `CQRS` to separate queries from the logic.

**Infrastructure:** layer to safe our domain logic from coupling with the framework or any infrastructure layers.

This layer consists of:
- `Adapters`; Which has for example `DatabasManager` this is adapter for Laravel Databas Manager.
- `Exceptions`; contains all mapped exceptions from framework to our domain logic.
- `Models`; Which is ORM models.
- `Repositories`; the implementation of domain's repositories interfaces.
- `Providers`; to register the mapping between domain interfaces and infrastructure.

**Libraries:** Our core domain logic.

This layer consists of:
- `Reservation`; Reservation domain logic library, it has its repositories interfaces, and it has the creation of reservation or any related logic.
- `Common`; the common services that can support our domain, to avoide to use freamwork services.
- `Passenger`; Passenger domain logic.
- `Services`; is service domain, we be used when multiple libraries need to collaborate with each other to accomplish a specific task. For example, in the case of the ReservationService, I implemented it to facilitate the specific task of handling reservations.

**tests:** Our code test, here I implement the most critical part in the task, as an example which is the ReservationService library.



### What Done in the task:
- [x] Use PHP (Laravel || Lumen)
- [x] use Mysql as a database
- [x] Follow Rest standards to create the APIs
- [x] Use PHP-cs-fixer rules
- [x] Create unit test
- [ ] Use any logger method or library to have a full trace for all APIs requests with a correlation Id for each request
- [ ] Secure your API with a fixed API key or fixed JWT token
- [X] API documentation [ Postman ] Swagger is plus.
- [X] Database/ Data migration script(s).
- [X] Deployment script “using containers is better”.

## APIs:
- [x] List Orders
- [x] Create order
- [x] Read Order
- [ ] Update Order
- [x] Delete Order (soft-delete)


Note:
--
1. There are some areas for development here. May I write them for the time being for the time being, such as using DTOs instead of relying on Eloquent Models to safeguard the domain logic and prevent accidental misuse of model actions. Alternatively, we could consider a DataMapper solution like Doctrine ORM, which would allow us to use entities directly without the need for an additional DTO layer.
2. My solution here may be a bit complex, but we don't go for this approach for every business case. It depends on the specific business needs and the required scalability.# My hypothesis and thinking for future steps:


# Important Documents
- [Database Digram](./ERD.png)
- [How to Run the application](./HowToRun.md)
- [Postman Collection](./Reservations.postman_collection.json)  
