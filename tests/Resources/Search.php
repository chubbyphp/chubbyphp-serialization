<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Resources;

final class Search
{
    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $perPage;

    /**
     * @var string|null
     */
    private $search;

    /**
     * @var string|null
     */
    private $sort;

    /**
     * @var string|null
     */
    private $order;

    /**
     * @var Item[]
     */
    private $items;

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     *
     * @return self
     */
    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     *
     * @return self
     */
    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param null|string $search
     *
     * @return self
     */
    public function setSearch($search): self
    {
        $this->search = $search;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param null|string $sort
     *
     * @return self
     */
    public function setSort($sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param null|string $order
     *
     * @return self
     */
    public function setOrder($order): self
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return Item|null
     */
    public function getMainItem()
    {
        if ([] === $this) {
            return null;
        }

        return reset($this->items);
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param Item[] $items
     *
     * @return self
     */
    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }
}
