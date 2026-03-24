<?php

namespace App\Service;

use App\Repository\SoftwareVersionRepository;

class SoftwareMatcher
{
    private SoftwareVersionRepository $repo;

    public function __construct(SoftwareVersionRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Match a user's software & hardware version to the correct firmware.
     *
     * @param string $version
     * @param string $hwVersion
     * @return array
     */
    public function match(string $version, string $hwVersion): array
    {
        // 1. Validate inputs
        if (empty($version)) {
            return ['msg' => 'Version is required'];
        }

        if (empty($hwVersion)) {
            return ['msg' => 'HW Version is required'];
        }

        // 2. Define regex patterns
        $patternST = '/^CPAA_[0-9]{4}\.[0-9]{2}\.[0-9]{2}(_[A-Z]+)?$/i';
        $patternGD = '/^CPAA_G_[0-9]{4}\.[0-9]{2}\.[0-9]{2}(_[A-Z]+)?$/i';
        $patternLCI_CIC = '/^B_C_[0-9]{4}\.[0-9]{2}\.[0-9]{2}$/i'; // B_C_1234.01.01
        $patternLCI_NBT = '/^B_N_G_[0-9]{4}\.[0-9]{2}\.[0-9]{2}$/i';
        $patternLCI_EVO = '/^B_E_G_[0-9]{4}\.[0-9]{2}\.[0-9]{2}$/i';

        // 3. Detect HW type
        $stBool = false;
        $gdBool = false;
        $isLCI = false;
        $lciHwType = '';

        if (preg_match($patternST, $hwVersion)) {
            $stBool = true;
        }

        if (preg_match($patternGD, $hwVersion)) {
            $gdBool = true;
        }

        if (preg_match($patternLCI_CIC, $hwVersion)) {
            $isLCI = true;
            $lciHwType = 'CIC';
            $stBool = true;
        } elseif (preg_match($patternLCI_NBT, $hwVersion)) {
            $isLCI = true;
            $lciHwType = 'NBT';
            $gdBool = true;
        } elseif (preg_match($patternLCI_EVO, $hwVersion)) {
            $isLCI = true;
            $lciHwType = 'EVO';
            $gdBool = true;
        }

        if (!$stBool && !$gdBool) {
            return [
                'versionExist' => false,
                'msg' => 'There was a problem identifying your software. Contact us for help.',
                'link' => '',
                'st' => '',
                'gd' => ''
            ];
        }

        // 4. Normalize version
        if (strtolower($version[0]) === 'v') {
            $version = substr($version, 1);
        }

        // 5. Search DB
        $softwareVersions = $this->repo->findAll();

        foreach ($softwareVersions as $row) {

            // 3.3.3.mmipri.c
            // B_C_1234.01.01
            // $isLCI = true;
            // $lciHwType = 'CIC';
            // $stBool = true;


            if (strcasecmp($row->getSystemVersionAlt(), $version) !== 0) {
                continue;
            }

            // LCI logic
            if ($row->isLci() !== $isLCI) {
                continue;
            }

            if ($isLCI && stripos($row->getLciType(), $lciHwType) === false) {
                continue;
            }

            // Found match
            if ($row->isLatest()) {
                return [
                    'versionExist' => true,
                    'msg' => 'Your system is upto date!',
                    'link' => '',
                    'st' => '',
                    'gd' => ''
                ];
            } else {
                $stLink = $stBool ? $row->getStLink() : '';
                $gdLink = $gdBool ? $row->getGdLink() : '';
                $latestMsg = $isLCI ? 'v3.4.4' : 'v3.3.7';

                return [
                    'versionExist' => true,
                    'msg' => 'The latest version of software is ' . $latestMsg,
                    'link' => $row->getLink(),
                    'st' => $stLink,
                    'gd' => $gdLink
                ];
            }
        }

        // 6. No match found
        return [
            'versionExist' => false,
            'msg' => 'There was a problem identifying your software. Contact us for help.',
            'link' => '',
            'st' => '',
            'gd' => ''
        ];
    }
}