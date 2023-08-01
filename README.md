# asmos

Application & Server Monitoring System (ASMOS) online marking system for Majlis Peperiksaan Malaysia (MPM).

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

This project was developed on [Laravel Homestead](https://laravel.com/docs/master/homestead) and I highly recommend you use either that or [Laravel Valet](https://laravel.com/docs/master/valet), to get the optimal server configuration and no errors through installation.

### Installing

Clone the repository somewhere on your disk and enter to the repository:

```
git clone https://github.com/plisca/asmos.git
cd muet-online-marking
```

This package ships with a **.env.example** file in the root of the project.

You must rename this file to just **.env**

**Note:** Make sure you have hidden files shown on your system.

Laravel project dependencies are managed through the [PHP Composer tool](http://getcomposer.org/). The first step is to install the depencencies by navigating into your project in terminal and typing this command:

```
composer install
```

In order to install the Javascript packages for frontend development, you will need the [Node Package Manager](https://www.npmjs.com/), and optionally the [Yarn Package Manager](https://code.facebook.com/posts/1840075619545360) by Facebook (Recommended)

If you only have NPM installed you have to run this command from the root of the project:

```
npm install
```

If you have Yarn installed, run this instead from the root of the project:

```
yarn
```

You must create your database on your server and on your **.env** file update the following lines:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=muet
DB_USERNAME=homestead
DB_PASSWORD=secret
```

Change these lines to reflect your new database settings.

It's time to see if your database credentials are correct.

We are going to run the built in migrations to create the database tables:

```
php artisan migrate
```

You should see a message for each table migrated, if you don't and see errors, than your credentials are most likely not correct.

We are now going to set the administrator account information. To do this you need to navigate to [this file](https://github.com/plisca/muet-online-makring) and change the name/email/password of the Administrator account.

You can delete the other dummy users, but do not delete the administrator account or you will not be able to access the backend.

Now seed the database with:

```
php artisan db:seed
```

You should get a message for each file seeded, you should see the information in your database tables.

After your project is installed you must run this command to link your public storage folder for user avatar uploads:

```
php artisan storage:link
```

## Running the tests

After your project is installed, make sure you run the test suite to make sure all of the parts are working correctly. From the root of your project run:

```
phpunit
```

```
phpcs -n --extensions=php app
```

You will see a dot(.) appear for each of the hundreds of tests, and then be provided with the amount of passing tests at the end. There should be no failures with a fresh install.

### Break down into end to end tests

Explain what these tests test and why

```
Give an example
```

### And coding style tests

Explain what these tests test and why

```
Give an example
```

## Deployment

Add additional notes about how to deploy this on a live system

## Built With

* [Laravel](https://laravel.com/docs/5.8/releases) - The web framework used
* [Vue](https://vuejs.org) - The javascript framework used
* [Composer](https://getcomposer.org) - PHP Dependency Management
* [NPM](https://www.npmjs.com) - Node Package Manager

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/plisca/) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/plisca/stpm/tags).

## Authors

* **Huzaifah Mazaly** - *Initial work* - [Plisca](https://github.com/plisca)

See also the list of [contributors](https://github.com/plisca/stpm/contributors) who participated in this project.

## License

This project is licensed under the Majlis Peperiksaan Malaysia (MPM) - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Inspiration
* etc
