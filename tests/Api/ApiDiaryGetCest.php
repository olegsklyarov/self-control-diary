<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Diary;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ApiDiaryGetCest
{
    private ?Diary $diary = null;

    public function _before(ApiTester $I): void
    {
        $user = $I->createUser();
        $I->haveInRepository($user);

        $this->diary = new Diary($user, new \DateTimeImmutable('2021-02-04'));
        $I->haveInRepository($this->diary);
    }

    public function testSuccess(ApiTester $I): void
    {
        $I->wantToTest('GET /api/diary/{notedAt} (success)');
        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendGet('/api/diary/2021-02-04');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'uuid' => $this->diary->getUuid()->toString(),
            'notes' => $this->diary->getNotes(),
            'notedAt' => $this->diary->getNotedAt()->format(\DateTime::ATOM),
        ]);
    }

    public function testInvalidNotedAtValue(ApiTester $I): void
    {
        $I->wantToTest('GET /api/diary/{notedAt} (invalid noted_at value)');
        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendGet('/api/diary/20210201');
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => HttpCode::BAD_REQUEST,
            'message' => 'Invalid noted_at value.',
        ]);
    }

    public function testDiaryNotFound(ApiTester $I): void
    {
        $I->wantToTest('GET /api/diary/{notedAt} (diary not found)');
        $token = $I->doAuthAndGetJwtToken($I);
        $I->amBearerAuthenticated($token);
        $I->sendGet('/api/diary/2021-02-01');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'code' => HttpCode::NOT_FOUND,
            'message' => 'Diary not found.',
        ]);
    }

    public function testUnauthorised(ApiTester $I): void
    {
        $I->wantToTest('GET /api/diary/{notedAt} (unauthorized)');
        $I->sendGet('/api/diary/2021-02-04');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
}
