<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UploadedInvoiceFileRepository")
 */
class UploadedReceiptFile extends UploadedFile
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Withdraw", inversedBy="receipts", cascade={"persist"})
     */
    private $withdraw;

    public function __construct(string $path, Withdraw $withdraw, Person $uploader)
    {
        parent::__construct($path, $uploader);

        $this->setWithdraw($withdraw);
    }

    public function getWithdraw(): ?Withdraw
    {
        return $this->withdraw;
    }

    public function setWithdraw(?Withdraw $withdraw): self
    {
        $this->withdraw = $withdraw;
        $this->withdraw->addReceiptFile($this);

        return $this;
    }
}
