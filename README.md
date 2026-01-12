# Nexus Logistics Core API

## Overview

Nexus Logistics Core is a robust, modular API for managing industrial logistics, built with **Symfony 6.4**, **PHP 8.3**, and strictly following **Hexagonal Architecture (Ports & Adapters)** and **CQRS** (Command Query Responsibility Segregation) principles.

<img width="1914" height="888" alt="preview" src="https://github.com/user-attachments/assets/2f195791-32ba-44ee-add0-faf76a63349d" />


## Features

- **Strict Hexagonal Architecture**: Separation of Domain, Application, and Infrastructure layers.
- **CQRS Pattern**: Distinct Command, Query, and Event buses using Symfony Messenger.
- **Rich Domain Model**: Use of Value Objects (`ShippingId`, `Weight`) and Domain Events.
- **Asynchronous Processing**: Ready for async event handling (e.g., Audit Logging).
- **OpenAPI / Swagger UI**: Fully documented API endpoints.

## Tech Stack

- **Framework**: Symfony 6.4
- **Language**: PHP 8.3
- **Database**: PostgreSQL 16
- **Queue/Cache**: Redis
- **ORM**: Doctrine (with custom Types)
- **Containerization**: Docker & Docker Compose

## Project Structure

```
src/
├── Shipping/
│   ├── Application/    # Use Cases (Commands, Queries, Handlers)
│   ├── Domain/         # Entities, Value Objects, Repository Interfaces
│   └── Infrastructure/ # Controllers, Persistence (Doctrine)
├── shared/             # Shared Kernel (Bus Interfaces, Base Classes)
└── Audit/              # Audit Subdomain (Event Listeners)
```

## Getting Started

### Prerequisites

- Docker & Docker Compose

### Installation

1. **Clone the repository**
2. **Start the containers**:
   ```bash
   docker-compose up -d --build
   ```
3. **Run Migrations**:
   ```bash
   docker-compose exec app php bin/console doctrine:migrations:migrate
   ```

### API Documentation

Access the Swagger UI at:
**http://localhost:8000/api/doc**

### Available Endpoints

- `POST /api/v1/shipping/orders`: Create a new shipping order.
- `GET /api/v1/shipping/orders/{id}`: Retrieve order details.
- `POST /api/v1/shipping/orders/{id}/assign`: Assign a carrier to an order.
- `POST /api/v1/shipping/orders/{id}/cancel`: Cancel an order.

## Architecture Highlights

- **Command Bus**: handles state changes (Create, Assign, Cancel).
- **Query Bus**: handles reads (Get Order), returning clean DTOs.
- **Event Bus**: decouples side effects (e.g., Audit Logging listens to `ShippingOrderCreatedEvent` and `ShippingOrderCancelledEvent`).
- **Value Objects**: Validations (e.g., Weight limits, UUID formats) are encapsulated in the domain.

## License

Proprietary
