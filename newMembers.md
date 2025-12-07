
# New committee member info

This file has some information on the esac website, the design patterns we use and Laravel that might be useful for getting started with development.

## Design patterns

The ESAC website uses a bunch of design patterns. 
Some of them are standard in Laravel, and some of them are not necessarily a part of Laravel.  
This document assumes some general programming experience, 
but if you find something confusing feel free to write something about it and open a PR :)

### MVC (Model View Controller)

This is the basic pattern laravel is build around. The main building block of the website are models, views and controllers.  
In general, you can see models as an abstraction of a database table with some extra handy features.  
In laravel, views are basically fancy html templates that determine what a page (or component) will look like.  
Controllers kind of tie them together: usually a controller will listen to some routes/http endpoints
and then fetch data from the db, transform data, call other functionality and often render views.  
You can find these files in `app/Models` `resources/views` and `app/Http/Controllers`.  
If you want more info, take a look at [Wikipedia](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller) and the laravel docs for [Models](https://laravel.com/docs/12.x/eloquent), [Views](https://laravel.com/docs/12.x/views) and [Controllers](https://laravel.com/docs/12.x/controllers).

### Repository

This pattern isn't used in laravel by default, so you can't find laravel documentation about it.  
Basically a repository acts as a wrapper around database queries so that you don't have to write those in different controllers etc.  
Repositories are located in the `app/Repositories` folder. All repositories (should) implement the `IRepository` interface.  
This makes sure all repositories have the same basic methods available (for example create, update, delete, findBy). 
A repository can also have extra methods that are specific to a model.  
Some of the main benefits of repositories are reusability, flexibility and testing.
Because you put the actual "raw" database query in a repository, you only have to change one place if, for example, some fields in a model are renamed.
It also helps prevent mistakes, typos, etc. by keeping the way some basic queries are done consistently over the application.  
If you want some extra info, take a look at [this article](https://kritimyantra.com/blogs/laravel-repository-pattern-explained).
It does some things a bit differently than us, but it explains the basic concept pretty well.

### Event(listeners)

The event pattern is a nice way to decouple certain parts of the application. 
I do want to immediately give a warning that using a lot of events can make the code flow a bit more difficult to understand, especially without a good IDE.  
The pattern is built up of events and listeners. They are located in the `app/Events` and `app/Listeners` folders.  
As the name suggests, events correspond to things happening in the application. Listeners then "subscribe" and listen for various events that might happen.  
Take this example in the `UserController`: when updating a user it might happen that their membership type changes. 
When this happens we want to do some things like log that this happened and update some mail lists.  
However, it's not very nice to do all of this straight away in the controller. For example, for separation of concerns and testability.   
In some cases the task that needs to happen can also take longer than you'd want in a controller (like updating maillists or sending an email).
In these cases a listener can also be used to queue the task so the controller stays fast.  
If you want to read more about events, take a look at the [documentation](https://laravel.com/docs/12.x/events).

## Laravel/ ESAC website specific things

Laravel has some very nice features, but some of them can be a bit confusing if you're not familiar with them.  
I based this section mostly on my own experience and the things I found confusing, but please add more sections if you encountered things you found confusing.  
I also included some "interesting" things in the ESAC website that might be hurdles.

### Http request flow

It might be helpful to have a rough overview of how an http request travels through the application  
1. Laravel receives the request and forwards it to the router for dispatching. 
2. If there is route defined for the request, the router will run any route specific middleware and then dispatch the request to (in general) a controller.
3. The controller receives the request and processes it. This can entail a lot of different things depending on what the controller is for.
4. After the controller is done with processing, the request is sent back out through the middlewares.
5. The response is sent to the client.

### Dependency injection/the service container

One of the main things I found quite confusing coming from a basic java background was the way dependency injection is done.  
If you just learned programming you're probably used to functions you write being explicitly called somewhere, but for quite a few things this is not really the case in laravel.  
Take for example a controller: you don't ever really explicitly make a controller like `$controller = new UserController(...)`.  
Instead controllers are instantiated by the laravel service container when a route is visited.  
The main point for the service container is to do dependency injection. 
This comes down to you thinking "hmm, I could use a UserRepository here" and if you then typehint it in your controller/method, the container can inject an instance into the method.  
More concretely, if you have a controller action `update(Request $request, User $user, MailListFacade $mailListFacade)`, 
the service container will "magically" inject an instance of `MailListFacade` into your code when calling the controller action.  
This is really nice for testing and not having to worry about instantiating classes yourself all the time.  
A sidenote here: the `Request` and `User` objects are gotten via the route that calls the controller action.  
You can read more about the service container [here](https://laravel.com/docs/12.x/container), but I wouldn't worry about it too much.

### Middleware

Middleware is a convenient mechanism for inspecting and filtering HTTP requests that come into the application.  
Thing for example of checking if a user is properly authenticated and if not, redirecting to the login page. 
Middleware is located in the `app/Http/Middleware` folder.  
If you want to read more about it, see the [docs](https://laravel.com/docs/12.x/middleware).

### The vue frontend

Most pages in the website are made using blade templates and follow the "standard" laravel way of writing frontend.  
However some features (agenda, zekeringen, application form beheer) are written using a Vue2 frontend that communicated with API endpoints in the backend.  
You can find the frontend code under `resources/assets/vue`.  
If you wish to make changes to the frontend: you can do so in the files in the component folders. Do keep in mind that we use **Vue2**, which is deprecated,
so a lot of tutorials online etc will be assuming you're using Vue3.  
To change data that is passed/fetched through the api, you might have to change things in `store/modules/agenda.js`, 
or the API subfolders on the vue side and on the laravel side in the `ApiController` and the controllers in the `Controllers/Api` folder.  
The vue components are then incorporated in blade templates to be actually rendered.