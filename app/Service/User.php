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
    private $login;

    /** @var string */
    private $email;

    /** @var string */
    private $commitAuthorName;

    /** @var string */
    private $commitAuthorEmail;

    public function __construct() {}

    public static function getByLogin(string $login): self
    {
        $user = new self();
        $user->setLogin($login);

        return $user->init();
    }

    private function init(): self
    {
        if ($this->login === null) {
            throw new \Exception('User login not defined!');
        }

        /* load data */
        $data = (new Data(App::DATA_USERS))->setReadFrom(__METHOD__)->readCached();
        if (!isset($data[$this->login])) {
            throw new \Exception('User with login ' . $this->id . ' not found');
        }

        $userData = $data[$this->login];

        $this->id = $userData['id'];
        $this->name = $userData['name'];
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

        $userData = $data[$this->login];
        $userData['committerName'] = $this->commitAuthorName;
        $userData['committerEmail'] = $this->commitAuthorEmail;

        $users->setData([$this->login => $userData] + $users->read());
        $users->write();
    }
}
