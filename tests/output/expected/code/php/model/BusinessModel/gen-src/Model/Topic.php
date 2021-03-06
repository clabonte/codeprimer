<?php
/*
 * This file has been generated by CodePrimer.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodePrimer\Tests\Model;

use \DateTimeInterface;

/**
 * Class Topic
 * A topic regroups a set of posts made by various authors
 * @package CodePrimer\Tests\Model
 */
class Topic
{
    /** @var string The topic title */
    protected $title = '';

    /** @var string|null The topic description */
    protected $description = null;

    /** @var User[]|null List of authors who are allowed to post on this topic */
    protected $authors = null;

    /** @var Post[]|null List of posts published on this topic */
    protected $posts = null;

    /** @var DateTimeInterface|null Time at which the post was created */
    protected $created = null;

    /** @var DateTimeInterface|null Last time at which the post was updated */
    protected $updated = null;

    /**
     * Topic default constructor
     * @var string $title The topic title
     */
    public function __construct(
        string $title
    ) {
        $this->title = $title;
    }

    /**
     * @codeCoverageIgnore
     * @param string $title
     * @return Topic
     */
    public function setTitle(string $title): Topic
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @codeCoverageIgnore
     * @param string|null $description
     * @return Topic
     */
    public function setDescription(?string $description): Topic
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @codeCoverageIgnore
     * @param User[]|null $authors
     * @return Topic
     */
    public function setAuthors(?array $authors): Topic
    {
        $this->authors = $authors;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return User[]|null
     */
    public function getAuthors(): ?array
    {
        return $this->authors;
    }

    /**
     * @codeCoverageIgnore
     * @param Post[]|null $posts
     * @return Topic
     */
    public function setPosts(?array $posts): Topic
    {
        $this->posts = $posts;
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return Post[]|null
     */
    public function getPosts(): ?array
    {
        return $this->posts;
    }

    /**
     * @codeCoverageIgnore
     * @param DateTimeInterface|null $created
     * @return Topic
     */
    public function setCreated(?DateTimeInterface $created): Topic
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
     * @return Topic
     */
    public function setUpdated(?DateTimeInterface $updated): Topic
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


    /**
     * Checks if this Topic contains at least one instance of a given User
     * @param User $authors
     * @return bool
     */
    public function containsAuthor(User $authors): bool
    {
        $result = false;

        if (isset($this->authors)) {
            $result = array_search($authors, $this->authors) !== false;
        }

        return $result;
    }

    /**
     * Adds a User instance to this Topic if it is not already present
     * @param User $authors
     * @return Topic
     */
    public function addAuthor(User $authors): Topic
    {
        if (!$this->containsAuthor($authors)) {
            if (!isset($this->authors)) {
                $this->authors = [];
            }
            $this->authors[] = $authors;
        }

        return $this;
    }

    /**
     * Removes all instances of a given User from this Topic
     * @param User $authors
     * @return Topic
     */
    public function removeAuthor(User $authors): Topic
    {
        if (!isset($this->authors)) {
            return $this;
        }

        $keys = array_keys($this->authors, $authors);
        foreach (array_reverse($keys) as $key) {
            unset($this->authors[$key]);
        }

        return $this;
    }

    /**
     * Checks if this Topic contains at least one instance of a given Post
     * @param Post $posts
     * @return bool
     */
    public function containsPost(Post $posts): bool
    {
        $result = false;

        if (isset($this->posts)) {
            $result = array_search($posts, $this->posts) !== false;
        }

        return $result;
    }

    /**
     * Adds a Post instance to this Topic if it is not already present
     * @param Post $posts
     * @return Topic
     */
    public function addPost(Post $posts): Topic
    {
        if (!$this->containsPost($posts)) {
            if (!isset($this->posts)) {
                $this->posts = [];
            }
            $this->posts[] = $posts;
        }

        return $this;
    }

    /**
     * Removes all instances of a given Post from this Topic
     * @param Post $posts
     * @return Topic
     */
    public function removePost(Post $posts): Topic
    {
        if (!isset($this->posts)) {
            return $this;
        }

        $keys = array_keys($this->posts, $posts);
        foreach (array_reverse($keys) as $key) {
            unset($this->posts[$key]);
        }

        return $this;
    }
}
