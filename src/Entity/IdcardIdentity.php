<?php

namespace Tourze\UserIDIdcardBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\UserIDBundle\Contracts\IdentityInterface;
use Tourze\UserIDBundle\Model\Identity;
use Tourze\UserIDIdcardBundle\Repository\IdcardIdentityRepository;

#[ORM\Entity(repositoryClass: IdcardIdentityRepository::class)]
#[ORM\Table(name: 'ims_user_identity_idcard', options: ['comment' => '身份证'])]
class IdcardIdentity implements IdentityInterface, \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    public const IDENTITY_TYPE = 'idcard';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[ORM\Column(type: Types::STRING, length: 18, nullable: false, options: ['comment' => '身份证号'])]
    private string $idcard;

    #[ORM\ManyToOne]
    private ?UserInterface $user = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIdcard(): string
    {
        return $this->idcard;
    }

    public function setIdcard(string $idcard): static
    {
        $this->idcard = $idcard;

        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getIdentityValue(): string
    {
        return $this->getIdcard();
    }

    public function getIdentityType(): string
    {
        return self::IDENTITY_TYPE;
    }

    public function getIdentityArray(): \Traversable
    {
        yield new Identity($this->getId(), $this->getIdentityType(), $this->getIdentityValue(), [
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
        ]);
    }

    public function getAccounts(): array
    {
        return [];
    }

    public function __toString(): string
    {
        return $this->getIdcard();
    }
}
