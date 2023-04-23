<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RequestDtoUsers
{
    #[Assert\NotBlank]
    #[Assert\Expression(
        expression: "this.getHotelId() >= 0 "
    )]
    private int $hotelId;

    #[Assert\NotBlank]
    #[Assert\Expression(
        expression: "this.getPage() >= 1 "
    )]
    private int $page;

    #[Assert\NotBlank]
    #[Assert\Expression(
        expression: "this.getSort() in ['firstName', 'lastName', 'roles', 'email', 'birthday', 'registrationDate']"
    )]
    private string $sort;

    #[Assert\NotBlank]
    #[Assert\Expression(
        expression: "this.getOrder() in ['asc', 'desc']"
    )]
    private string $order;

    public function __construct(int $hotelId, int $page, string $sort, string $order)
    {
        $this->hotelId = $hotelId;
        $this->page = $page;
        $this->sort = $sort;
        $this->order = $order;
    }

    public function getHotelId(): int
    {
        return $this->hotelId;
    }

    public function setHotelId(int $hotelId): RequestDtoUsers
    {
        $this->hotelId = $hotelId;

        return $this;
    }

    public function getSort(): string
    {
        return $this->sort;
    }

    public function setSort(string $sort): RequestDtoUsers
    {
        $this->sort = $sort;

        return $this;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    public function setOrder(string $order): RequestDtoUsers
    {
        $this->order = $order;

        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): RequestDtoUsers
    {
        $this->page = $page;

        return $this;
    }
}
