<?php

namespace App\Controller;

use App\Entity\Diary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Security;

class DiaryParamResolver implements ArgumentValueResolverInterface
{
    private const PARAM_NOTED_AT = 'noted_at';

    private EntityManagerInterface $entityManagerInterface;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManagerInterface, Security $security)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->security = $security;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === Diary::class && $request->get(self::PARAM_NOTED_AT) !== null;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $paramNotedAt = $request->get(self::PARAM_NOTED_AT);

        yield $this->isValidDateFormat($paramNotedAt)
            ? $this->entityManagerInterface->getRepository(Diary::class)->findOneBy([
                'notedAt' => new \DateTimeImmutable($paramNotedAt),
                'user' => $this->security->getUser(),
            ])
            : null;
    }

    private function isValidDateFormat(string $date): bool
    {
        if (preg_match("/(\d{4})-(\d{2})-(\d{2})/", $date, $matches)) {
            return checkdate((int)$matches[2], (int)$matches[3], (int)$matches[1]);
        }
        return false;
    }
}
