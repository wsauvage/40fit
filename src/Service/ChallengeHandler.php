<?php

namespace App\Service;

use App\Enum\PostCategoryEnum;

class PostHandler
{
    public function __construct()
    {
    }

    public function getPosts(?PostCategoryEnum $categoryEnum = null): array
    {
        $metaDataDir = __DIR__.'/../../data/post/';
        $files = glob($metaDataDir.'*.json');
        $posts = [];

        foreach ($files as $file) {
            $jsonData = json_decode(file_get_contents($file), true);
            if ($jsonData) {
                if ($categoryEnum === null || ($jsonData['category'] ?? null) === $categoryEnum->value) {
                    $posts[] = $jsonData;
                }
            }
        }

        // Tri par datePublished décroissante
        usort($posts, function (array $a, array $b) {
            $dateA = isset($a['datePublished']) ? new \DateTime($a['datePublished']) : new \DateTime('1970-01-01');
            $dateB = isset($b['datePublished']) ? new \DateTime($b['datePublished']) : new \DateTime('1970-01-01');

            // On compare B à A pour avoir l'ordre décroissant
            return $dateB <=> $dateA;
        });

        return $posts;
    }

}