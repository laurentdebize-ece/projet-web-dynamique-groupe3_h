```php

class DbTableController {

    protected string $TableName; // 'Users'
    protected string $TableClassName; // 'User'
    


    public function get(string sql) {
        // code PDO pour fetch les trucs de la base en SQL
        // pdo->fetchObject($this->TableClassName);
    }

    public function 
}

class User {
int sexe, age, taille;
}

class UserController extends DbTableController {
    public function __construct() {
        $this->TableName = 'Users';
        $this->TableClassName = User::class;
    }

    public get()
}

$users
```