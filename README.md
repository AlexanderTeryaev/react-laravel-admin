[![pipeline status](https://gitlab.com/marmelade/marmelade-backend/badges/master/pipeline.svg)](https://gitlab.com/marmelade/marmelade-backend/pipelines)  
 
# Marmelade Backend  
  
The marmalade backend is made of PHP Laravel 6. It uses a MySQL database. It allows you to run the API (GraphQL) necessary for the mobile application and the Portal but it also serves as a BackOffice for content editing (questions, quizzes, authors...) and many other things.  

## Run locally  
   
**Prerequisites**
  
- [docker](https://docs.docker.com/get-docker/)
- [docker-compose](https://docs.docker.com/compose/install/)
   
**Launch**

Inside de repository root folder run command:

```
$ cd marmelade-backend
$ docker-compose up # You will have to leave the terminal open, the logs of all containers will be displayed.
```
OR
```
$ cd marmelade-backend
$ docker-compose up -d # To run containers in daemon 
$ docker-compose logs -f app # To display backend container in live
$ docker-compose down # To stop all containers running in background
```
  
To display running containers you should run the commande `docker-composer ps`
You must have the following result (All states to **Up**) :

```
$ docker-compose ps
         Name                           Command               State                 Ports
-----------------------------------------------------------------------------------------------------------
marmelade-backend_app_1          /usr/local/bin/docker-entr ...   Up      0.0.0.0:8080->80/tcp
marmelade-backend_db_1           docker-entrypoint.sh mysqld      Up      0.0.0.0:3307->3306/tcp, 33060/tcp
marmelade-backend_phpmyadmin_1   /docker-entrypoint.sh apac ...   Up      0.0.0.0:8088->80/tcp
```

**Manipulate**

If you want to start an SSH session in one of the containers you can enter the command `docker exec -it <CONTAINER_NAME> /bin/bash` as follow:
  
```
$ docker exec -it marmelade-backend_app_1 /bin/bash
docker@xxxxxxxxxxxx:/var/www/html$
```

**If this is the first time you start the project locally, you must enter these three commands in the backend container:**

```
docker@xxxxxxxxxxxx:/var/www/html$ composer install # This will install all the necessary dependencies
docker@xxxxxxxxxxxx:/var/www/html$ php artisan migrate # This will create the necessary tables in database
docker@xxxxxxxxxxxx:/var/www/html$ php artisan storage:link # This will make stored data accessible
```

**Endpoints**

| Type | Endpoint | Description | Auth (login/password) |
| -- | -- | -- | -- |
| Back-Office | [127.0.0.1:8080](http://1270.0.0.1:8080) | Platform to manage content (Users, questions, quizzes...) | elon@marmelade-app.fr/toto42sh |
| GraphQL | [127.0.0.1:8080/graphql](http://1270.0.0.1:8080/graphql) | GraphQL Endpoint | Check [User Authentication](#user-authentication ) section |
| GraphQL Playground | [127.0.0.1:8080/graphql-playground](http://1270.0.0.1:8080/graphql-playground) | Visualize the graphQL schema, try query/mutation... | Public |
| phpMyAdmin | [127.0.0.1:8088](http://127.0.0.1:8088/index.php) | Managing database, tables, columns... | root/toto42sh |
| Images | [http://127.0.0.1:8080/storage/](http://127.0.0.1:8080/storage/) | Where the images are stored | Public |

**Seeding**
  
Laravel includes a simple method of seeding your database with test data using seed classes. All seed classes are stored in the `backend/database/seeds` directory.   
  
In the backend folder (In backend docker container)  
  
```
docker@xxxxxxxxxxxx:/var/www/html$ php artisan db:seed # Will launch all the seeders     
OR
docker@xxxxxxxxxxxx:/var/www/html$ php artisan db:seed --class=UsersTableSeeder # To launch a specific seeder  
```
  
## Preproduction
Preproduction, useful to test new functionalities before putting it into production, running on Kubernetes, hosted on AWS via the EKS service.
The preproduction domain is **preprod.mrmld.net**

**Endpoints:**

| Type | Endpoint | Description | Auth (login/password) |
| -- | -- | -- | -- |
| Back-Office | [preprod.mrmld.net](https://preprod.mrmld.net) | Platform to manage content (Users, questions, quizzes...) | elon@marmelade-app.fr/toto42sh |
| GraphQL | [gql.preprod.mrmld.net](https://gql.preprod.mrmld.net) | GraphQL Endpoint | Check [User Authentication](#user-authentication ) section |
| GraphQL Playground | [gql.preprod.mrmld.net/graphql-playground](https://gql.preprod.mrmld.net/graphql-playground) | Visualize the graphQL schema, try query/mutation... | Public |
| Images | [images.preprod.mrmld.net/](http://images.preprod.mrmld.net/) | Where the images are stored | Public |

## Production
Production environment is where the backend is actually put into operation for intended uses by end users
The Production domain is **mrmld.net**

**Endpoints:**

| Type | Endpoint | Description | Auth (login/password) |
| -- | -- | -- | -- |
| Back-Office | [mrmld.net](https://mrmld.net) | Platform to manage content (Users, questions, quizzes...) | You admin account |
| GraphQL | [gql.mrmld.net](https://gql.mrmld.net) | GraphQL Endpoint | Check [User Authentication](#user-authentication ) section |
| GraphQL Playground | [gql.mrmld.net/graphql-playground](https://gql.mrmld.net/graphql-playground) | Visualize the graphQL schema, try query/mutation... | Public |
| Images | [images.mrmld.net/](http://images.mrmld.net/) | Where the images are stored | Public |


## User Authentication (JsonWebToken)

For user authentication we use [JWT](https://jwt.io). Authentication is done in two different ways depending on the client. 

Secure authentication for the mobile application works with a temporary password sent by email. To do this, you have to use the following mutations:
- To login use `sendLoginCode` mutation to receive by email a temporary login code and `loginWithEmail` mutation to login with the code received.
- To signup use `sendRegisterCode` mutation to receive by email a temporary register code and `registerWithEmail` mutation to register with the code received.

For the Portal app users (Web) in exchange for a valid email password combination you'll get a JWT token. You will need to use the following mutations:
- For register `createGroup` and `verifyEmail` mutation
- For login just use `login` mutation and get a token

**Token use**

No matter the client, you must send the token in the header to each graphql request by following [Bearer Authentication](https://swagger.io/docs/specification/authentication/bearer-authentication/). The key of the header is `Authorization`, and the token must be preceeded by `Bearer `, here is an example below:
```
Authorization: Bearer eyJhbGciOiJIUzI1NiIXVCJ9TJV...r7E20RMHrHDcEfxjoYZgeFONFh7HgQ
```

**Token Refresh**

At some point the token will end up being invalid, you will see it because all queries/mutations you make will return an auth error with the message: `Invalid Token`. 
At this point you can use the `refreshToken` mutation by providing the expired token, you will get a new token to use as the first one. 
If the refreshToken mutation returns a `Token error`, it means that you have exceeded the maximum renewal date of the token (2 weeks after expiration).


**Logout**

To disconnect the user simply call the `logout` mutation, which will invalidate the token.

## Versioning  
  
We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://gitlab.com/marmelade/marmelade/tags).  
  
## License  
  
No license. All rights reserved