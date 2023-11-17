<?php

namespace Service;

use Admin\App;

class User
{
    /** @var string This is login  */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $password;

    /** @var string */
    private $login;

    /** @var string */
    private $email;

    /** @var string */
    private $commitAuthorName;

    /** @var string */
    private $commitAuthorEmail;

    public function __construct() {}

    public static function getByLogin(string $login): ?self
    {
        $user = new self();

        return $user->loadBy($login);
    }

    public static function getByLoginAndPass(string $login, string $password): ?self
    {
        $user = new self();

        return $user->loadBy($login, $password);
    }

    private function loadBy(string $login, ?string $password = null): ?self
    {
        /* load data */
        $data = (new Data(App::DATA_USERS))->setReadFrom(__METHOD__)->readCached();

        if (!isset($data[$login])) {
            return null;
        }

        $userData = $data[$login];

        if ($password !== null && $userData[\User\Auth::USER_PASS] !== md5($password)) {
            return null;
        }

        $this->login = $login;
        $this->id = $userData['id'];
        $this->name = $userData['name'];
        $this->password = $userData['password'];
        $this->email = $userData['email'] ?? '';
        $this->commitAuthorName = $userData['committerName'] ?? '';
        $this->commitAuthorEmail = $userData['committerEmail'] ?? '';

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getCommitAuthorName(): ?string
    {
        return $this->commitAuthorName;
    }

    public function setCommitAuthorName(string $name): self
    {
        $this->commitAuthorName = $name;
        return $this;
    }

    public function getCommitAuthorEmail(): ?string
    {
        return $this->commitAuthorEmail;
    }

    public function setCommitAuthorEmail(string $email): self
    {
        $this->commitAuthorEmail = $email;
        return $this;
    }

    public function save(): void
    {
        if ($this->login === null) {
            throw new \Exception('User login not defined!');
        }

        $users = (new Data(App::DATA_USERS))->setReadFrom(__METHOD__);
        $data = $users->read();

        if (array_key_exists($this->login, $data)) {
            // UPDATE USER
            $userData = $data[$this->login];
            $userData['password'] = $this->password;
            $userData['committerName'] = $this->commitAuthorName;
            $userData['committerEmail'] = $this->commitAuthorEmail;

            $users->setData([$this->login => $userData] + $users->read());
            $users->write();
        } else {
            // CREATE USER
            $userData = [];
            $userData[\User\Auth::USER_NAME] = $this->name;
            $userData[\User\Auth::USER_PASS] = $this->password;
            $userData[\User\Auth::USER_ID] = $this->id;
            $userData[\User\Auth::USER_LOGIN] = $this->login;

            $users->setData([$this->login => $userData] + $users->read());
            $users->write();
        }

    }
}
