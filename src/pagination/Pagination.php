<?php

declare(strict_types=1);

namespace App\Pagination;

/**
 * Класс для работы с пагинацией страниц.
 *
 * @author Антон Грабовский <agrabovsky2020@gmail.com>
 */
class Pagination
{
    /**
     * Вычисляет количество страниц
     *
     * @param integer $total Количество элементов, полученные из базы данных.
     * @param integer $itemsPerPage Количество элементов на странице.
     * @return integer Возвращает количество страниц.
     */
    public static function getPageCount(int $total, int $itemsPerPage): int
    {
        try {
            $pageCount = (int) ceil($total / $itemsPerPage);

            return $pageCount;
        } catch (\Exception $e) {
            return 1;
        }
    }
}
