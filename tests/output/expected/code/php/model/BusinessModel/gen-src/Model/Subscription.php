<?php
/*
 * This file has been generated by CodePrimer.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodePrimer\Tests\Model;

use CodePrimer\Tests\Dataset\Plan;
use \DateTimeInterface;

/**
 * Class Subscription
 * The subscription bought by a user to user our services
 * @package CodePrimer\Tests\Model
 */
class Subscription
{
    /** @var User The user to which this subscription belongs */
    protected $user;

    /** @var Plan The plan subscribed by this user in our billing system */
    protected $plan;

    /** @var DateTimeInterface The date at which the subscription must be renewed */
    protected $renewal;

    /** @var DateTimeInterface|null Time at which the post was created */
    protected $created = null;

    /** @var DateTimeInterface|null Last time at which the post was updated */
    protected $updated = null;

    /**
     * Subscription default constructor
     * @var User $user The user to which this subscription belongs
     * @var Plan $plan The plan subscribed by this user in our billing system
     * @var DateTimeInterface $renewal The date at which the subscription must be renewed
     */
    public function __construct(
        User $user,
        Plan $plan,
        DateTimeInterface $renewal
    ) {
        $this->user = $user;
        $this->plan = $plan;
        $this->renewal = $renewal;
    }

    /**
     * @codeCoverageIgnore
     * @param User $user
     * @return Subscription
     */
    public function setUser(User $user): Subscription
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @codeCoverageIgnore
     * @param Plan $plan
     * @return Subscription
     */
    public function setPlan(Plan $plan): Subscription
    {
        $this->plan = $plan;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return Plan
     */
    public function getPlan(): Plan
    {
        return $this->plan;
    }

    /**
     * @codeCoverageIgnore
     * @param DateTimeInterface $renewal
     * @return Subscription
     */
    public function setRenewal(DateTimeInterface $renewal): Subscription
    {
        $this->renewal = $renewal;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return DateTimeInterface
     */
    public function getRenewal(): DateTimeInterface
    {
        return $this->renewal;
    }

    /**
     * @codeCoverageIgnore
     * @param DateTimeInterface|null $created
     * @return Subscription
     */
    public function setCreated(?DateTimeInterface $created): Subscription
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return DateTimeInterface|null
     */
    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    /**
     * @codeCoverageIgnore
     * @param DateTimeInterface|null $updated
     * @return Subscription
     */
    public function setUpdated(?DateTimeInterface $updated): Subscription
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return DateTimeInterface|null
     */
    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

}
