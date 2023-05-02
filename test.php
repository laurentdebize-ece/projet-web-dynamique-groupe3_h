<?
require_once 'src/models.php';
require_once 'src/database/ClassQL.php';

// DatabaseController::getInstance()->createTable("fuck", ClassQL::getTableDefForClass(User::class));
// User::select(null);
// SELECT * FROM `Users`

$user = new User("salut", "jamy", "lol", "sjidsijds");
// // echo ClassQL::getTableDefForClass(User::class);
User::select(null);




// User::select(0);
// } catch (Exception $e) {
//     echo "error";
//     var_dump($e);
// }
// echo ClassQL::getTableDefForClass(User::class);