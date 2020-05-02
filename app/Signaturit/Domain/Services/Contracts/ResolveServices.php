<?php

namespace App\Signaturit\Domain\Services\Contracts;

use App\Signaturit\Domain\Models\Roles\RoleType;

class ResolveServices
{
    const TIE_RESULT = 'there is a tie between plaintiff and defendant';
    const PLAINTIFF_WINS = 'plaintiff wins the lawsuit';
    const DEFENDANT_WINS = 'defendant wins the lawsuit';

    protected $plaintiffSignatures;
    protected $defendantSignatures;

    /**
     * Construct function
     *
     * @param array $plaintiffSignatures
     * @param array $defendantSignatures
     */
    public function __construct(array $plaintiffSignatures, array $defendantSignatures)
    {
        $this->plaintiffSignatures = $plaintiffSignatures;
        $this->defendantSignatures = $defendantSignatures;
    }

    /**
     * Prepare parameters to evaluate
     *
     * @return string
     */
    public function getWinner(): string
    {
        $plaintiffPoints = $this->getTotalPoints($this->plaintiffSignatures);
        $defendantPoints = $this->getTotalPoints($this->defendantSignatures);

        if ($plaintiffPoints > $defendantPoints) {
            return self::PLAINTIFF_WINS;
        }

        if ($plaintiffPoints < $defendantPoints) {
            return self::DEFENDANT_WINS;
        }

        return self::TIE_RESULT;
    }

    /**
     * Sum equivalent points from roles signatures
     *
     * @param array $party
     * @return int
     */
    protected function getTotalPoints(array $party = []): int
    {
        $points = 0;
        $hasKingSignature = $this->checkHasKingSignature($party);

        foreach ($party as $signature) {
            $points += ($hasKingSignature === true && $signature === RoleType::VALIDATOR) ? 0 :
                RoleType::get($signature)['points'];
        }

        return $points;
    }

    /**
     * Check if party have a signature by king role
     *
     * @param array $party
     * @return bool
     */
    protected function checkHasKingSignature(array $party = []): bool
    {
        return (in_array(RoleType::KING, $party, true)) ? true : false;
    }
}
