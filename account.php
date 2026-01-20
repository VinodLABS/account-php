<?php
class user
{
    public $id;
    public $name;
    public $email;
    private $password;   

    function __construct($id, $name, $email, $password)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    function checkPassword($inputpassword){
        return $this->password === $inputpassword;
    }

    function getProfile(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email
        ];
    }
}


$student1 = new user(1, "John Doe", "john@example.com", "password123");

$student2 = new user(2, "Jane Smith", "jane@example.com", "password456");
$student3 = new user(3, "Bob Johnson", "bob@example.com", "password789");

$students = [$student1, $student2, $student3];

foreach($students as $student) {
    $profile = $student->getProfile();
    echo "ID: " . $profile['id'] . " | Name: " . $profile['name'] . " | Email: " . $profile['email'] . "<br>";
}
?>
