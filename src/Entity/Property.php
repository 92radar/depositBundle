<?php

namespace _92radar\DepositBundle\Entity;

use _92radar\DepositBundle\Entity;
use _92radar\DepositBundle\Entity\UserInterface;

interface PropertyInterface


{

    public const STATES = [
            "address",
            "infos",
            "rooms",
            "energy",
            "furnished",
            "equipments",
            "benefits",
            "rent",
            "charges",
            "services",
            "deposit",
            "availability",
            "description",
            "title",
            "images",
        ];

    public function getUid(): ?string;
    public function getRent(): ?int;
    public function getCharges(): ?int;
    public function isFurnished(): ?bool;
    public function getDeposit(): ?int;
    public function setDeposit(?int $deposit): self;
    public function getState(): ?string;
    public function setState(string $state): self;
    public function getOwner(): ?UserInterface;
}

