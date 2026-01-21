<?php
// class user
// {
//     public $id;
//     public $name;
//     public $email;
//     private $password;

//     function __construct($id, $name, $email, $password)
//     {
//         $this->id = $id;
//         $this->name = $name;
//         $this->email = $email;
//         $this->password = $password;
//     }
//     function __get($property)
//     {
//         if ($property === "password") {
//             return "Access denied";
//         }
//         return $this->$property;
//     }

//     function __set($property, $value)
//     {
//         if ($property === "password") {
//             echo "Password cannot be changed directly<br>";
//         } else {
//             $this->$property = $value;
//         }
//     }
//     function checkPassword($inputpassword)
//     {
//         return $this->password === $inputpassword;
//     }

//     function getProfile()
//     {
//         return [
//             'id' => $this->id,
//             'name' => $this->name,
//             'email' => $this->email
//         ];
//     }
// }


// $student1 = new user(1, "John Doe", "john@example.com", "password123");
// $student2 = new user(2, "Jane Smith", "jane@example.com", "password456");
// $student3 = new user(3, "Bob Johnson", "bob@example.com", "password789");

// $students = [$student1, $student2, $student3];

// foreach ($students as $student) {
//     $profile = $student->getProfile();
//     echo "ID: " . $profile['id'] . " | Name: " . $profile['name'] . " | Email: " . $profile['email'] . "<br>";
// }



// class Animal {
//     public function eat() {
//         echo "Animal is eating";
//     }
// }

// class Dog extends Animal {
//     public function bark() {
//         echo "Dog is barking";
//     }
// }

// $dog = new Dog();
// $dog->eat();   
// $dog->bark();  



final class ParentClass {
    public function greetss() {
        echo "Hello Parent";
    }
    
    protected function secretMessage() {
        echo "You should not see this";
    }
}

class ChildClass extends ParentClass {
    final public function greet() {
        parent::greetss();
        parent::secretMessage();
        echo " & Hello Child";
    }
}

$obj = new ChildClass();
$obj->greet();
