<?php

namespace App\Serializers;

use Flugg\Responder\Serializers\SuccessSerializer;
use League\Fractal\Pagination\PaginatorInterface;

class PaginationApiSerializer extends SuccessSerializer
{
     /**
     * Serialize the paginator.
     *
     * @param PaginatorInterface $paginator
     *
     * @return array
     */
    public function paginator(PaginatorInterface $paginator)
    {
        $currentPage = (int)$paginator->getCurrentPage();
        $lastPage = (int)$paginator->getLastPage();

        $pagination = [
            'total' => (int)$paginator->getTotal(),
            'per_page' => (int)$paginator->getPerPage(),
            'current_page' => $currentPage,
            'last_page' => $lastPage,
            'count' => (int)$paginator->getCount(),
            'prev_page_url' => $paginator->getUrl($currentPage - 1),
            'next_page_url' => $paginator->getUrl($currentPage + 1),
            'from' => (($currentPage-1)*$paginator->getPerPage()) + 1,
            'to' => $paginator->getTotal() > $currentPage*$paginator->getPerPage() ?
                $currentPage*$paginator->getPerPage() : $paginator->getTotal(),
        ];

        return ['pagination' => $pagination];
    }
}
