<?php

namespace App\Controller;

use App\Helper\DashboardHelper;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DashboardChartController extends AbstractController
{
    /**
     * @param DashboardHelper $helper
     * @return JsonResponse
     * @throws Exception
     * @Route("/dashboard/chart/total_return_by_date", name="dashboard_chart__total_return_by_date")
     */
    public function listTotalReturnByDate(DashboardHelper $helper)
    {
        $dataSet = $helper->getDataSetOfTotalReturnByDate();

        return new JsonResponse($dataSet);
    }

    /**
     * @param DashboardHelper $helper
     * @return JsonResponse
     * @throws Exception
     * @Route("/dashboard/chart/return_by_date", name="dashboard_chart__return_by_date")
     */
    public function listReturnByDate(DashboardHelper $helper)
    {
        $dataSet = $helper->getDataSetOfReturnByDate();

        return new JsonResponse($dataSet);
    }
}
