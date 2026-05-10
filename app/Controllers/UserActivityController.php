<?php

namespace App\Controllers;

use App\Models\UserActivityModel;
use CodeIgniter\Controller;

class UserActivityController extends BaseController
{
    protected $activityModel;

    public function __construct()
    {
        $this->activityModel = new UserActivityModel();
    }

    public function index()
    {
        // Only admin should see this, but since there's no auth middleware yet, we just serve it
        $data['logs'] = $this->activityModel->getLogsWithUserDetails();
        return view('dashboard/activity_logs', $data);
    }
}
