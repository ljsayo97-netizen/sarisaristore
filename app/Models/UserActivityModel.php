<?php

namespace App\Models;

use CodeIgniter\Model;

class UserActivityModel extends Model
{
    protected $table            = 'user_activity_logs';
    protected $primaryKey       = 'log_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'action', 'timestamp', 'ip_address'];

    protected $useTimestamps = false; // We manually set the timestamp

    public function getLogsWithUserDetails()
    {
        return $this->select('user_activity_logs.*, users.name as user_name, users.email as user_email')
                    ->join('users', 'users.id = user_activity_logs.user_id')
                    ->orderBy('user_activity_logs.timestamp', 'DESC')
                    ->findAll();
    }
}
