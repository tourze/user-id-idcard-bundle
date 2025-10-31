<?php

declare(strict_types=1);

namespace Tourze\UserIDIdcardBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\UserIDBundle\Contracts\IdentityInterface;
use Tourze\UserIDBundle\Model\Identity;
use Tourze\UserIDIdcardBundle\Repository\IdcardIdentityRepository;

#[ORM\Entity(repositoryClass: IdcardIdentityRepository::class)]
#[ORM\Table(name: 'ims_user_identity_idcard', options: ['comment' => '身份证'])]
class IdcardIdentity implements IdentityInterface, \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;
    public const IDENTITY_TYPE = 'idcard';

    #[ORM\Column(type: Types::STRING, length: 18, nullable: false, options: ['comment' => '身份证号'])]
    #[Assert\NotBlank(message: '身份证号不能为空')]
    #[Assert\Length(max: 18, maxMessage: '身份证号长度不能超过 {{ limit }} 位')]
    #[Assert\Regex(pattern: '/^[0-9]{17}[0-9Xx]$/', message: '身份证号格式不正确')]
    private string $idcard;

    #[ORM\ManyToOne]
    private ?UserInterface $user = null;

    public function getIdcard(): string
    {
        return $this->idcard;
    }

    public function setIdcard(string $idcard): void
    {
        $this->idcard = $idcard;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getIdentityValue(): string
    {
        return $this->getIdcard();
    }

    public function getIdentityType(): string
    {
        return self::IDENTITY_TYPE;
    }

    /**
     * @return \Generator<Identity>
     */
    public function getIdentityArray(): \Traversable
    {
        yield new Identity($this->getId() ?? '', $this->getIdentityType(), $this->getIdentityValue(), [
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
