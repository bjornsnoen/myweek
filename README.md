# My week

## What it is
It keeps track of what you do, and later tells you what you did

## How it does it
For right now it installs and enables a global githook that keeps track of any commit you make to any git repository,
and outputs that information for the past week when you run the command.

## How do I use it?
You install it anywhere you like by git cloning it and then adding the path to the bin directory to your PATH variable
however you so choose to do that. Finally do `composer install` in the app directory.
After that just run `myweek` from the cli.

## System requirements
* php version 7.4 or higher
* [composer](https://getcomposer.org)
* [Linux](https://linux.org) probably? It should work on mac. It probably won't run on windows though.
* [git](https://git-scm.com) is a requirement until provider configuration is implemented

## What future features can I expect?
@see [/TODO.md](TODO.md)

## How can I extend it?
Implement `\MyWeek\App\Api\EventProviderInterface` and add your provider to the app by adding a `yield` statement in the 
`\MyWeek\App\Services\EventProviderProvider` service.
Just don't break the law by ignoring the [GPL](https://www.gnu.org/licenses/gpl-3.0.en.html).

## Don't you have just like a phar executable I can use?
No