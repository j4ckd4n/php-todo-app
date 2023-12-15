# php-todo-app

A very basic TODO web application that is rendered using server-side PHP and Bootstrap for CSS. 

## Installation

You can deploy this tool using `docker compose up` while being in the same directory. This will spin up a docker container with the necessary resources.

To access the page, just type in `http://localhost/` in your browsers search bar.

## Usage

To add an new task, enter the name of the task in the text box and either click the ➕ sign or press enter.

![Creating a task](/images/image.png)

To delete a task, just click on the ❌ button next to the task.

![Delete task button](/images/image-1.png)

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

## Technical details

Everything is handled in the [`index.php`](/src/index.php) file, including server-side rendering and database handling. 

The database in question is SQLite3 and is stored in `/var/www/db`. This is also a volume that should be accessible on your machine as well under the `/php_todo/db/` folder.

Everything is hosted using dockerized instance of PHP with Apache and is defined in [`dockerfile`](/dockerfile) and [`docker-compose.yaml`](/docker-compose.yaml) files.

![couldn't be more simple](/images/89izab.gif)