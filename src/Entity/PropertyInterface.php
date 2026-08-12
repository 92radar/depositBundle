<?php

namespace aintreallydown\DepositBundle\Entity;

use aintreallydown\DepositBundle\Entity;
use aintreallydown\DepositBundle\Entity\UserInterface;

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
            "extrafields",
            "availability",
            "description",
            "title",
            "images",
        ];

    public function getUid(): ?string;
    public function getRent(): ?int;
    public function getCharges(): ?int;
    public function isFurnished(): ?bool;
    public function getExtrafields(): ?array;
    public function setExtrafields(?array $extrafields): self;
    public function getState(): ?string;
    public function setState(string $state): self;
    public function getOwner(): ?UserInterface;
}

