## Our starter kit for az related projects

By installing this project, you will have somes features availables, such as:

- Tables Models.
- Tailwind css as default framework and few html components.
- Laravel livewire package to build SPA project if you want.
- Login logiques and switch companies.
- Payment methods.

### NB: 
* This project use the components of the CSS library MaryUI
    You can find the documentation [here](https://v1.mary-ui.com).
    The differents icons used can be found [here](https://blade-ui-kit.com/blade-icons)
* It use [Livewire 4](https://livewire.laravel.com), [Laravel 11.*](https://laravel.com/docs/11.x/installation)

## How use it ?
- copy the file .env.example and rename it to .env, then put your configuration keys
- run composer install to install PHP/Laravel dependances
- run php artisan key:generate
- run npm install to install the Javascript dependances
- run npm run dev to compile the assets
- run php artisan serve and see the demo at [http://127.0.0.1:8000](http://127.0.0.1:8000)

Now you can start customize the project by adding your own colors and others pages.

_To send your projet on git, update the repository runing :_
git remote set-url origin  your-url . ( Ex : https://user/my-projet.git )
_Check the update by runing :_ git remote -v
_Push your code now :_ git push -u origin branch-name : ( Ex main or master )

