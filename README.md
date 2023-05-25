# Solution

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

Note:
--
1. There are some areas for development here. May I write them for the time being for the time being, such as using DTOs instead of relying on Eloquent Models to safeguard the domain logic and prevent accidental misuse of model actions. Alternatively, we could consider a DataMapper solution like Doctrine ORM, which would allow us to use entities directly without the need for an additional DTO layer.
2. My solution here may be a bit complex, but we don't go for this approach for every business case. It depends on the specific business needs and the required scalability.# My hypothesis and thinking for future steps:


# Important Documents
- [Database Digram](./ERD.png)
- [How to Run the application](./HowToRun.md)
