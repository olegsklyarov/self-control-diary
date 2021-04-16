<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Lead;
use App\Service\Lead\LeadService;
use App\Tests\FunctionalTester;

class FindLeadsWaitingForVerificationEmailCest
{
    private ?LeadService $leadService = null;

    public function _before(FunctionalTester $I): void
    {
        $this->leadService = $I->grabService(LeadService::class);
    }

    public function testSuccess(FunctionalTester $I): void
    {
        $leadIvan = new Lead('ivan@example.com', 'ivan-hashed-password');
        $I->haveInRepository($leadIvan);

        $leadPetr = new Lead('petr@example.com', 'petr-hashed-password');
        $leadPetr->setVerificationEmailSentAt(new \DateTimeImmutable('2021-06-07'));
        $I->haveInRepository($leadPetr);

        $leadAlex = new Lead('alex@example.com', 'alex-hashed-password');
        $leadAlex->setVerificationEmailSentAt(new \DateTimeImmutable('2021-06-07'));
        $leadAlex->setVerifiedEmailAt(new \DateTimeImmutable('2021-06-07'));

        $leads = $this->leadService->findLeadsForVerificationEmail();

        $I->assertIsArray($leads);
        $I->assertCount(1, $leads);

        $foundLead = $leads[0];

        $I->assertEquals('ivan@example.com', $foundLead->getEmail());
    }

    public function testFindLimit(FunctionalTester $I): void
    {
        for ($i = 0; $i < 11; ++$i) {
            $prefix = sprintf('user-%d', $i);
            $email = sprintf('%s@example.com', $prefix);
            $password = sprintf('%s-hashed-password', $prefix);
            $I->haveInRepository(new Lead($email, $password));
        }

        $I->assertCount(11, $I->grabEntitiesFromRepository(Lead::class));

        $leads = $this->leadService->findLeadsForVerificationEmail();

        $I->assertIsArray($leads);
        $I->assertCount(10, $leads);
    }
}
