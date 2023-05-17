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
- Enjoy :star_struck:

The project will install 2 docker containers:
- **MySQL Container**
- **Laravel Application Container**

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
- Exceptions
- Middlewares
- Units
- Utils
- ..etc

Moreover, the project has its database seeder to easily populate the database.

## API Documentation
You can find a dumped postman collection ([FLM Postman Collection](./FLM%20Case%20Study%20Postman%20Collection.json)) containing local environment variables, authentication tests and examples of the endpoints developed by the system.

