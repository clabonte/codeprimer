<?php
/*
 * This file has been generated by CodePrimer.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodePrimer\Tests\Event;

/**
 * Class RegistrationRequest
 * Event triggered when user wants to register with the application
 * @package CodePrimer\Tests\Event
 */
class RegistrationRequest
{
    /** @var string User email address */
    protected $email = '';

    /** @var string User password */
    protected $password = '';

    /** @var string|null User first name */
    protected $firstName = null;

    /** @var string|null User last name */
    protected $lastName = null;

    /** @var string|null The name used to identify this user publicly on the site */
    protected $nickname = null;

    /**
     * RegistrationRequest default constructor
     * @var string $email User email address
     * @var string $password User password
     */
    public function __construct(
        string $email,
        string $password
    ) {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @codeCoverageIgnore
     * @param string $email
     * @return RegistrationRequest
     */
    public function setEmail(string $email): RegistrationRequest
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @codeCoverageIgnore
     * @param string $password
     * @return RegistrationRequest
     */
    public function setPassword(string $password): RegistrationRequest
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @codeCoverageIgnore
     * @param string|null $firstName
     * @return RegistrationRequest
     */
    public function setFirstName(?string $firstName): RegistrationRequest
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @codeCoverageIgnore
     * @param string|null $lastName
     * @return RegistrationRequest
     */
    public function setLastName(?string $lastName): RegistrationRequest
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @codeCoverageIgnore
     * @param string|null $nickname
     * @return RegistrationRequest
     */
    public function setNickname(?string $nickname): RegistrationRequest
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return string|null
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

}
