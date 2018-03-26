<?php

namespace App\Models;


use App\Core\Collection\AppCollection;

class Profile extends Model
{
    protected $id;
    private $role;
    private $email;
    private $gender;
    private $number;
    private $course;
    private $username;
    private $lastName;
    private $firstName;
    private $lastLogin;
    private $dateJoined;
    private $passwordHash;
    private $loginAttempts;

    public function __construct(
        int $id = 0,
        string $username = '',
        string $email = '',
        string $firstName = '',
        string $lastName = '',
        string $passwordHash = '',
        string $gender = '',
        string $number = '',
        string $course = '',
        string $role = '',
        string $dateJoined = '',
        string $lastLogin = '',
        int $loginAttempts = 0
    )
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->passwordHash = $passwordHash;
        $this->gender = $gender;
        $this->number = $number;
        $this->course = $course;
        $this->role = $role;
        $this->dateJoined = $dateJoined;
        $this->lastLogin = $lastLogin;
        $this->loginAttempts = $loginAttempts;
    }

    public static function empty() :self { return new self; }
    public function getUsername() :string { return $this->username; }
    public function getEmail() :string { return $this->email; }
    public function getFirstName() :string { return $this->firstName; }
    public function getLastName() :string { return $this->lastName; }
    public function getPasswordHash() :string { return $this->passwordHash; }
    public function getGender() :string { return $this->gender; }
    public function getPhoneNumber() :string { return $this->number; }
    public function getRole() :string { return $this->role; }
    public function getDateJoined() :string { return $this->dateJoined; }
    public function getLastLogin() :string { return $this->lastLogin; }
    public function getLoginAttempts() :int { return $this->loginAttempts; }
    public function getCourse() :string { return $this->course; }

    public function setEmail(string $email = '') :void
    {
        $this->email = $email;
    }

    public function setFirstName(string $firstName = '') :void
    {
        $this->firstName = $firstName;
    }

    public function setLastName(string $lastName = '') :void
    {
        $this->lastName = $lastName;
    }

    public function setPassword(string $password = '') :void
    {
        $this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setGender(string $gender = '') :void
    {
        $this->gender = $gender;
    }

    public function setPhoneNumber(string $phoneNumber = '') :void
    {
        $this->number = $phoneNumber;
    }

    public function setRole(string $role = '') :void
    {
        $this->role = $role;
    }

    public function setLastLogin(string $date = '') :void
    {
        $this->lastLogin = $date;
    }

    public function setLoginAttempts(int $attempts = 0) :void
    {
        $this->loginAttempts = $attempts;
    }

    public function setCourse(string $course = '') :void
    {
        $this->course = $course;
    }

    public function saveAsData() :array
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'gender' => $this->gender,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'password' => $this->passwordHash,
            'joined' => $this->dateJoined,
            'last_login' => $this->lastLogin,
            'login_attempts' => $this->loginAttempts,
            'role' => $this->role,
            'number' => $this->number,
            'course' => $this->course
        ];
    }

    public function convertToModel(AppCollection $data) :IModel
    {
        return new self(
            $data->getInt('id'),
            $data->getString('username'),
            $data->getString('email'),
            $data->getString('first_name'),
            $data->getString('last_name'),
            $data->getString('password'),
            $data->getString('gender'),
            $data->getString('number'),
            $data->getString('course'),
            $data->getString('role'),
            $data->getString('joined'),
            $data->getString('last_login'),
            $data->getInt('login_attempts')
        );
    }
}
