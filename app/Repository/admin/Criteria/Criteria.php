<?php
/**
 * Created by   :  Ubaid
 * Project Name : Smart Pad Pro
 * Product Name : PhpStorm
 * Date         : 1-29-2019 4:14 PM
 * File Name    : Criteria.php
 */

namespace Repository\admin\Criteria;

use Repository\Admin\Contracts\RepositoryInterface as Repository;

abstract class Criteria
{
    public abstract function apply($model, Repository $repository);
}
