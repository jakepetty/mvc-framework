# Micro MVC PHP Framework
A micro MVC framework for PHP complete with a build environment. Comes with jQuery, Twitter Bootstrap 4 and Font Awesome pre-installed. This tiny framework was inspired by CakePHP and Larvel, and supports composer vendors, mysql and sqlite.

# Installation
Run the following commands inside the root directory
- `composer install`
- `npm install`

# Compiling JS and SCSS
Simply type the command `gulp` inside the root directory

# Setting up a MVC
## Model
**File**: `app/models/User.php`
```php
use App\AppModel;

class User extends AppModel {
    
}
```

## Controller
**File**: `app/controllers/UsersController.php`

```php
use App\AppController;

class UsersController extends AppController {
    // View: app/views/users/index.php
    public function index(){
        $user = new User();
        $users = $user->find();

        $this->view->render('users.index', compact('users'));
    }
    // View: app/views/users/view.php
    public function view($id = null){
        //
    }
    // View: app/views/users/login.php
    public function login(){
        //
    }
    public function logout(){
        //
    }
    // View: app/views/users/create.php
    public function create(){
        //
    }
    public function store(){
        //
    }
    // View: app/views/users/edit.php
    public function edit($id = null){
        //
    }
    public function update($id = null){
        //
    }
    public function delete($id = null){
        //
    }
}
```

## View
**File**: `app/views/users/index.php`
```php
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Username</th>
            <th class="text-right">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user){ ?>
        <tr>
            <th><?php echo $user['id'];?></th>
            <th><?php echo $user['username'];?></th>
            <th class="text-right">
                <div class="btn-group">
                    <a href="<?php echo route('users.view', $user['id']);?>" class="btn btn-sm btn-primary">View</a>
                    <a href="<?php echo route('users.edit', $user['id']);?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="<?php echo route('users.delete', $user['id']);?>" class="btn btn-sm btn-primary">Delete</a>
                </div>
            </th>
        </tr>
        <?php } ?>
    </tbody>
</table>
```

# Manipulating Data

**File**: `app/controllers/UsersController.php`
```php
    use App\User;
    ...
    $user = new User();
    $user->save([
        'username' => 'demo',
        'password'=> 'somePassword'
    ]);
```

## Updating Data

**File**: `app/controllers/UsersController.php`
```php
    use App\User;
    ...
    $user = new User();
    $user->save([
        'username' => 'demo',
        'password'=> 'somePassword'
    ], $id);
```

## Deleting Data

**File**: `app/controllers/UsersController.php`

```php
    use App\User;
    ...
    $user = new User();
    $user->delete($id);
```

## Retrieving Data
**File**: `app/controllers/UsersController.php`

```php
    use App\User;
    ...
    class UsersController extends AppController {
        public function index(){
            $model = new User();

            // Return multiple rows based on conditions
            $conditions = [
                'name' => 'John'
            ];

            $fields = [
                'id',
                'username'
            ];
            
            $limit = 10;

            $users = $model->find($conditions, $fields, $limit);
            
            $this->view->render('users.index', compact('users'));
        }

        public function view($id = null){
            $model = new User();

            // Retrieve a single row based on ID
            $user = $model->read($fields, $id);

            // Return a single row based on conditions
            $user = $model->findFirst($conditions, $fields);

            $this->view->render('users.view', compact('user'));
        }
    }
```

# User Authentication
## Logging In
**File**: `app/controllers/UsersController.php`
```php
    use App\Auth;
    use App\User;
    use App\Request;
    
    class UsersController extends AppController {
        public function login(){
            $user = new User();
            $data = $user->findFirst(Request::all());
            if($data){
                Auth::login($data);
                redirect('users.index');
            }
        }

        $this->view->render('users.login');
    }
```

**File**: `app/views/users/login.php`
```php
    <?php echo \App\Flash::show();?>
    <form method="POST" action="<?php echo route('users.login');?>">
        <input type="text" name="username">
        <input type="password" name="password">
        <button>Login</button>
    </form>
```

## Logging Out
**File**: `app/controllers/UsersController.php`

```php
    use App\Auth;
    
    class UsersController extends AppController {
        public function logout(){
            Auth::logout();

            redirect('users.login');
        }
    }
```

# Flash Messages
Flash messages utilize Bootstrap 4's alert component

**File**: `app/controllers/UsersController.php`
```php
use App\Flash;

class UsersController extends AppController {
    public function index(){

        Flash::message('Some messsage', 'success');
        // OR
        Flash::message('Some messsage', 'error');
        // OR
        Flash::message('Some messsage', 'danger');
        // OR
        Flash::message('Some messsage', 'warning');
        // OR
        Flash::message('Some messsage', 'info');

        $this->view->render('users.index');
    }
}
```

**File**: `app/views/users/index.php`

```php
<?php echo \App\Flash::show();?>
```
