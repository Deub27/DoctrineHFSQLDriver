<?php

namespace TBCD\Doctrine\HFSQLDriver\Tests;

use Symfony\Component\Finder\Finder;

trait TestHelperTrait
{

    /**
     * @return string
     */
    public function generateTableName(): string
    {
        $alphabet = range("A", "Z");
        $uniq = str_split(uniqid());
        foreach ($uniq as &$char) {
            if (is_numeric($char)) {
                $char = $alphabet[(int)$char];
            }
        }
        return implode('', $uniq);
    }

    /**
     * @return void
     */
    public static function clearFiles(): void
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__ . '/..')->name('*.fic');
        foreach ($finder as $file) {
            unlink($file->getRealPath());
        }
    }
}