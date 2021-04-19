<?php
/**
 * Created by   :  Ubaid
 * Project Name : Smart Pad Pro
 * Product Name : PhpStorm
 * Date         : 1-29-2019 4:14 PM
 * File Name    : CriteriaInterface.php
 */

namespace Repository\admin\Contracts;

use Repository\Admin\Criteria\Criteria;

interface CriteriaInterface
{
    /**
     * @param bool $status
     *
     * @return $this
     */
    public function skipCriteria($status = TRUE);

    /**
     * @return mixed
     */
    public function getCriteria();

    /**
     * @param Criteria $criteria
     *
     * @return $this
     */
    public function getByCriteria(Criteria $criteria);

    /**
     * @param Criteria $criteria
     *
     * @return $this
     */
    public function pushCriteria(Criteria $criteria);

    /**
     * @return $this
     */
    public function  applyCriteria();
}
