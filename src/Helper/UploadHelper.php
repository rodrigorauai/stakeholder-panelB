<?php
/**
 * Copyright © 2019 Rafael de Souza - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Rafael de Souza <rafael.de.souza@outlook.com>, 06 2019
 */

namespace App\Helper;


use App\Entity\Contract;
use App\Entity\Person;
use App\Entity\UploadedDigitizedContractFile;
use App\Entity\UploadedPersonFile;
use App\Entity\UploadedReceiptFile;
use App\Entity\Withdraw;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UploadHelper
{
    /**
     * @var string
     */
    private $privatePath;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(string $privatePath, EntityManagerInterface $entityManager, TokenStorageInterface $storage)
    {
        $this->privatePath = $privatePath;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $storage;
    }

    public function savePrivateFile(File $file, string $prefix)
    {
        $filename = $prefix.'-'.uniqid().'.'.$file->guessExtension();

        return $file->move($this->privatePath, $filename);
    }

    public function saveReceipt(File $file, Withdraw $withdraw)
    {
        $savedFile = $this->savePrivateFile($file, 'receipt');

        /** @var Person $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $object = new UploadedReceiptFile($savedFile->getPathname(), $withdraw, $user);
        $this->entityManager->persist($object);

        return $savedFile;
    }

    public function saveDigitizedContract(File $file, Contract $contract)
    {
        $savedFile = $this->savePrivateFile($file, 'digitized-contract');

        /** @var Person $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $object = new UploadedDigitizedContractFile($savedFile->getPathname(), $contract, $user);
        $this->entityManager->persist($object);

        return $savedFile;
    }

    public function savePersonFile(string $name, File $file, Person $person)
    {
        $savedFile = $this->savePrivateFile($file, 'person-file');

        /** @var Person $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $object = new UploadedPersonFile($name, $savedFile->getPathname(), $person, $user);
        $this->entityManager->persist($object);

        return $savedFile;
    }
}