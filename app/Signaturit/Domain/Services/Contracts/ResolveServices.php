<?php

namespace App\Signaturit\Domain\Services\Contracts;

use App\Signaturit\Domain\Models\Contracts\Roles\RoleType;

class ResolveServices
{
    const TIE_RESULT = 'there is a tie between plaintiff and defendant';
    const PLAINTIFF_WINS = 'plaintiff wins the lawsuit';
    const DEFENDANT_WINS = 'defendant wins the lawsuit';

    protected $plaintiffSignatures;
    protected $defendantSignatures;
    protected $totalPlaintiffPoints;
    protected $totalDefendantPoints;
    protected $loserRoles;

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
     * Prepare parameters to evaluate and get the winner of lawsuit
     *
     * @return string
     */
    public function getWinner(): string
    {
        $this->totalPlaintiffPoints = $this->getTotalPoints($this->plaintiffSignatures);
        $this->totalDefendantPoints = $this->getTotalPoints($this->defendantSignatures);

        if ($this->totalPlaintiffPoints > $this->totalDefendantPoints) {
            return self::PLAINTIFF_WINS;
        }

        if ($this->totalPlaintiffPoints < $this->totalDefendantPoints) {
            return self::DEFENDANT_WINS;
        }

        return self::TIE_RESULT;
    }

    /**
     * Calculate How many points need one of the parts to win to the
     * opposition when appears a empty signature on his part
     *
     * @return string
     */
    public function calculatePointsForWinner(): string
    {
        $result = [];
        $winner = $this->getWinner();

        switch ($winner) {
            case self::DEFENDANT_WINS:
                $this->loserRoles = $this->plaintiffSignatures;
                $result = $this->checkHasSignature($this->plaintiffSignatures, RoleType::EMPTY_SIGNATURE) ?
                    $this->findRoleTypesToWin(
                        ($this->totalDefendantPoints - $this->totalPlaintiffPoints)
                    ) : [];
                break;

            case self::PLAINTIFF_WINS:
                $this->loserRoles = $this->defendantSignatures;
                $result = $this->checkHasSignature($this->defendantSignatures, RoleType::EMPTY_SIGNATURE) ?
                    $this->findRoleTypesToWin(
                        ($this->totalPlaintiffPoints - $this->totalDefendantPoints)
                    ) : [];
                break;
        }

        return implode($result);
    }

    /**
     * Find how many points need to win the trial and return the respective role types
     *
     * @param int $diffPoints
     *
     * @return array
     */
    protected function findRoleTypesToWin(int $diffPoints): array
    {
        ++$diffPoints;
        $sumPoints = 0;
        $result = [RoleType::get('points', $diffPoints)['id'] ?? null];

        if ($diffPoints === RoleType::get('id', RoleType::KING)['points'] &&
            $this->checkHasSignature($this->loserRoles, RoleType::VALIDATOR)) {
            $result[0] = null;
        }

        if (!$result[0]) {
            while ($sumPoints < $diffPoints) {
                $closestPoint = $this->getClosestPoint(($diffPoints - $sumPoints));
                $roleTypeByPoint = RoleType::get('points', $closestPoint);
                if ($roleTypeByPoint && $sumPoints < $diffPoints) {
                    if (($roleTypeByPoint['id'] === RoleType::VALIDATOR) &&
                        $this->checkHasSignature($this->loserRoles, RoleType::KING)) {
                        --$sumPoints;
                    } else {
                        $result[] = $roleTypeByPoint['id'];
                        $sumPoints += $roleTypeByPoint['points'];
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Return the closest point from role points, for example if i have 12, the closes point is 5 (King Role type)
     * Also will check if the closest point is 5 (King Role)
     *
     * @param int $search
     * @return mixed|null
     */
    protected function getClosestPoint(int $search) {
        $pointsFromRoles = $this->unsetKingFromRolesValidator($search);

        if (!in_array($search, $pointsFromRoles, true)) {
            $pointsFromRoles[] = $search;
            asort($pointsFromRoles);
            $pointsFromRoles = array_values($pointsFromRoles);
            $keyOfSearch = array_keys($pointsFromRoles, $search);

            return $pointsFromRoles[array_shift($keyOfSearch) - 1];
        }

        return $search;
    }

    /**
     * In some case is possible that we need a king point point and validator point to get
     * the minimum points to win to the opposite part, but its important to remember if exist a king signature
     * the validator point is null, so will be need to disable king point from role points array
     * to obtain the minimum value (NNN not KV)
     *
     * @param int $value
     * @return array
     */
    protected function unsetKingFromRolesValidator(int $value): array
    {
        $mod = ($value > RoleType::get('id', RoleType::KING)['points']) ?
            ($value % RoleType::get('id', RoleType::KING)['points']) : 0;

        $pointsFromRoles = RoleType::getValuesByAttribute('points');

        // If is odd or have V signature, delete the king option point
        if ($mod & 1 || $this->checkHasSignature($this->loserRoles, RoleType::VALIDATOR)) {
            $pointsFromRoles = array_diff(
                $pointsFromRoles,
                [(RoleType::get('id', RoleType::KING)['points'])]
            );
        }

        return $pointsFromRoles;
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
        $hasKingSignature = $this->checkHasSignature($party, RoleType::KING);

        foreach ($party as $signature) {
            $points += ($hasKingSignature === true && $signature === RoleType::VALIDATOR) ? 0 :
                RoleType::get('id', $signature)['points'];
        }

        return $points;
    }

    /**
     * Check if party have a signature by king role
     *
     * @param array $party
     * @param string $signature
     * @return bool
     */
    protected function checkHasSignature(array $party, string $signature): bool
    {
        return (in_array($signature, $party, true)) ? true : false;
    }
}
