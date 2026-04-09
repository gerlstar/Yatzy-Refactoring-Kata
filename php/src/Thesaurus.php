<?php

declare(strict_types=1);

namespace Yatzy;

class Thesaurus
{
    private $thesaurus;

    public function __construct(array $thesaurus)
    {
        $this->thesaurus = $thesaurus;
    }

    public function getSynonyms(string $word): string
    {
        if (array_key_exists($word, $this->thesaurus)) {
            return json_encode([
                'word'     => $word,
                'synonyms' => $this->thesaurus[$word],
            ]);
        }

        return json_encode([
            'word'     => $word,
            'synonyms' => [],
        ]);
    }
}
