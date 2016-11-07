<?php

/* 
 * Access Profiler API from Yii.
 * Supports Online Donations, Pledges and Membership Renewals
 * @author David Webb <david@dpwlabs.com>
 * 
 * 
 */

namespace dpwlabs\profiler;

use Yii;
use yii\base\Component;

class Profiler extends Component
{
    public $config = [];
    
    public function init()
    {
    }
    
    public function getConfig() {
        return $this->config;
    }
}