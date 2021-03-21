<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiDiaryPathCest
{
    private ?Diary $diary = null;

    public function _before(ApiTester $I): void
    {
        $user = $I->createUser();
        $I->haveInRepository($user);

        $this->diary = new Diary($user, new \DateTimeImmutable('2021-03-21'));
        $I->haveInRepository($this->diary);
    }

    public function testSuccess(ApiTester $I): void
    {
        $I->wantToTest('PATCH /api/diary (success)');

        $token = $I->doAuthAndGetJwtToken($I);

        $I->amBearerAuthenticated($token);
        $I->sendPatch('/api/diary', [
            'notedAt' => '2021-03-21',
            'notes' => 'My diary note',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            'uuid' => $this->diary->getUuid()->toString(),
            'notes' => 'My diary note',
            'notedAt' => $this->diary->getNotedAt()->format(\DateTime::ATOM),
        ]);
    }

    public function testSuccessPath(ApiTester $I): void
    {
        $I->wantToTest('PATCH /api/diary/{noted_at} (success)');
        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendPatch('/api/diary', [
            'notedAt' => '2021-03-21',
            'notes' => 'New note in my diary',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson([
            'uuid' => $this->diary->getUuid()->toString(),
            'notes' => 'New note in my diary',
            'notedAt' => $this->diary->getNotedAt()->format(\DateTime::ATOM),
        ]);
    }
}
