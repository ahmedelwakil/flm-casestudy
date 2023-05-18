<p>
    <h1>Food Label Maker</h1>
    <h3>Promo Codes Case Study</h3>

## About Project
This project is a small demo for users and admins to create and apply promo codes.

## Project Deployment

Steps on how to deploy (Make sure docker-compose is installed on your machine):
- Clone project on your local machine
- Open the command line and change the current directory to the project directory
- Create <b>[.env](./.env)</b> file and copy it's content from the <b>[.env.deploy](./deploy-docker/.env.deploy)</b>
- Run Command ```docker-compose up -d```
- Enjoy! :star_struck:

**Note that the deployment seeds the database and all passwords are ``flm123456``

The project will install 2 docker containers:
- **MySQL Container**
- **Laravel Application Container**

To access any of the containers run ```docker exec -it {container-name} bash```

To Run Unit Tests:
- Access the Application Container ```docker exec -it flm-api bash```
- Run the tests command ```./vendor/bin/phpunit```
- Make sure you seed the database after running the tests ```php artisan db:seed```

## API Documentation
You can find a dumped postman collection ([FLM Postman Collection](./FLM%20Case%20Study%20Postman%20Collection.json)) containing local environment variables, authentication tests and examples of the endpoints developed by the system.

## What is Implemented
- Docker Containers & Deployment (Application Container - Database Container)
- Authentication using [JWT](https://jwt-auth.readthedocs.io/en/develop/) (JSON Web Token)
- Authorization using [Middlewares](./app/Http/Middleware/Custom)
- Application Configuration
- Control - Service - Repository Architecture
- Database Design & Data [Migrations](./database/migrations)
- [Factories](./database/factories) & [Seeders](./database/seeders)
- Clean Code
  - Custom [Exceptions](./app/Exceptions)
  - Entities [Controllers](./app/Http/Controllers)
  - Entities [Services](./app/Services)
  - Entities [Repositories](./app/Repositories)
  - Entities [Models](./app/Models)
  - Entities [Creation Units](./app/Units)
  - Constant [Utility Classes](./app/Utils)
  - [Validation Classes](./app/Validators)
- Promo Code Validation using [Chain of Responsibility](https://refactoring.guru/design-patterns/chain-of-responsibility) Design Pattern
- Unit [Tests](./tests/Unit/PromoCodeServiceTest.php)
- Readme [File](./README.md)
- Postman API [Collection](./FLM%20Case%20Study%20Postman%20Collection.json)

## Project Architecture

The project is developed using **Controller - Service - Repository architecture**
- **Controller Layer**: Gather data from request, performs validation and pass user input data to service.
- **Service Layer**: The middleware between controller and repository. It is responsible for gathering data from controllers, performing business logic, and calling repositories for data manipulation.
- **Repository Layer**: Layer for interaction with models and performing DB operations.

This provides a clear separation of responsibilities and achieve many degrees of the **SOLID Principles** which reduces dependencies and make the project better in readability, maintainability, design patterns, and testability.

Each of this layer has its own **Abstract Base Class** which provides the common operations for this layer. 
- **BaseModel**
- **BaseRepository**
- **BaseService**
- **BaseController**

Also, you can find different code structures for single responsibilities such as:
- **Exceptions**
- **Middlewares**
- **Units**
- **Utils**
- **..etc**

Moreover, the project has its database seeder to easily populate the database.

